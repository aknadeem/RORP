<?php 
namespace App\Services;
use Illuminate\Support\Str;

class HelperFunctionService
{
	
	function getSlug($title)
    {
        $slug = Str::of($title)->slug('-');

        return $slug;
    }
}
?>