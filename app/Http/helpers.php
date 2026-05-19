<?php
use Illuminate\Http\Request;


function getCategories($id){
    $category = DB::table('category')->select('category_id','name')->where('category_id',$id)->first();
    return $category;
}

function getLocations(){
    $state = DB::table('location')->select('location_id','name')->where('location_id',$id)->first();
    return $state;
}