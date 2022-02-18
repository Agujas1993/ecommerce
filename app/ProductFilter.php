<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProductFilter extends QueryFilter
{

    public function rules(): array
    {
        return [
            'search' => 'filled',
            'from' => 'date_format:Y/m/d',
            'to' => 'date_format:Y/m/d',
            'searchSize' => 'filled',
            'subcategory' => 'exists:subcategories,id'


        ];
    }

    public function search($query, $search)
    {
        return $query->where(function($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
    });
    }

    public function searchSize($query, $search)
    {
        return $query->where(function($query) use ($search){
            return $query->whereHas('sizes', function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            });
        });
    }

    public function subcategory($query, $subcategory)
    {
        return $query->where('subcategory_id', $subcategory);
    }


    public function skills($query, $skills)
    {
        $subquery = DB::table('skill_user AS s')
            ->selectRaw('COUNT(s.id)')
            ->whereColumn('s.user_id', 'users.id')
            ->whereIn('skill_id', $skills);

        $query->addBinding($subquery->getBindings());

        $query->where(DB::raw("({$subquery->toSql()})"), count($skills));
    }

    public function from($query, $date)
    {
        $date = Carbon::createFromFormat('Y/m/d', $date);

        $query->whereDate('created_at', '>=', $date);
    }

    public function to($query, $date)
    {
        $date = Carbon::createFromFormat('Y/m/d', $date);

        $query->whereDate('created_at', '<=', $date);
    }

}
