<?php
namespace App\Repositories;

use Carbon\Carbon;

use App\Models\Warning;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class WarningRepository
{
    public function warnings($property): array
    {
        if($property) {

            $user = auth()->user();

            $unit = Unit::where('id', $property)
                ->where('id_owner', $user['id'])
                ->count();

            if($unit > 0) {

                $warnings = Warning::where('id_unit', $property)
                    ->orderBy('datecreated', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->get();

                foreach ($warnings as $warnKey => $warnValue) {
                    $warnings[$warnKey]['datecreated'] = Carbon::parse($warnValue['datecreated'])->format('d/m/Y');
                    $photoList = [];
                    $photos = explode(',', $warnValue['photos']);
                    foreach ($photos as $photo) {
                        if(!empty($photo)) {
                            $photoList[] = asset('storage/'.$photo);
                        }
                    }
                    $warnings[$warnKey]['photos'] = $photoList;
                }

            } else {
                return ['error' => 'Essa Unidade não é sua.'];
            }

        } else {
            return ['error' => 'Você deve informar a propriedade.'];
        }

        return ['error' => '', 'list' => $warnings];
    }

    public function warningFile($data)
    {
        $validator = Validator::make($data->all(), [
            'photo' => 'required|file|mimes:jpg,png'
        ]);

        if(!$validator->fails()) {
            $file = $data->file('photo')->store('public');
            $photo = asset(Storage::url($file));
        } else {
            return ['error' => $validator->errors()->first()];
        }

        return ['error' => '', 'photo' => $photo];
    }

    public function newWarning($data): array
    {
        $validator = Validator::make($data->all(), [
           'title' => 'required',
            'property' => 'required'
        ]);

        if(!$validator->fails()) {

            $list = $data->input('list');

            $newWarn = new Warning();
            $newWarn->id_unit = $data->input('property');
            $newWarn->title = $data->input('title');
            $newWarn->status = 'IN_REVIEW';
            $newWarn->datecreated = date('Y-m-d');

            if($list && is_array($list)) {
                $photos = [];
                foreach($list as $item) {
                    $url = explode('/', $item);
                    $photos[] = end($url);
                }
                $newWarn->photos = implode(',', $photos);
            } else {
                $newWarn->photos = '';
            }

            $newWarn->save();

        } else {
            return ['error' => $validator->errors()->first()];
        }

        return ['error' => ''];
    }
}
