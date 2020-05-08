<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Team;

class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        
        $team = Team::select('name')->get();
        $teams = [];
        for($i=0; $i < count($team); $i++){
            $arr = $team[$i]->name;
            $teams [] = $arr;
        }
        $teamOne = $teams;
        $teamTwo = $teams;
        $random_teamOne = $teamOne[mt_rand(0,sizeof($teamOne)-1)];
        $random_teamTwo = $teamTwo[mt_rand(0,sizeof($teamTwo)-1)];

        if($random_teamOne == $random_teamTwo)
        {
           
            $random_teamOne = '';
            $random_teamTwo = '';
            return redirect('/admin/matches');
        }
            $match = [$random_teamOne,$random_teamTwo];
            $winner = $match[mt_rand(0,sizeof($match)-1)];
           // echo $random_teamOne.' - '.$random_teamTwo.' Winner Team-'.$winner;
            
            $matchFixes = [];
            $matchFixes[] = ['teamOne'=>$random_teamOne, 'teamTwo'=>$random_teamTwo, 'winner'=>$winner];
            
            // if(!empty($matchFixes)){
            //     $matchFixes = collect($matchFixes);
            // }
            //return response()->json(['data'=>$matchFixes], 200);
       
        if(!empty($matchFixes)){
            $matches = collect($matchFixes);
            return response()->json(['matches' => $matches], 200);
        }else{
            return response()->json(['matches' => []], 200);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
