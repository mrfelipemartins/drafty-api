<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserTeam;
use App\Http\Resources\UserTeamResource;
use Auth;

class UserTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
      $team = new UserTeam;
      $team->name = $request->name;
      $team->badge = $request->badge;
      $team->budget = 10000;
      $team->user_id = Auth::user()->id;
      if($team->save()) {
        return response ([
          'status' => 'success',
          'data' => new UserTeamResource($team)
        ], 200);
      } else {
        return response ([
          'status' => 'error',
          'error' => 'store.error',
          'msg' => 'Ocorreu um erro ao criar o time'
        ], 500)
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $userTeam = new UserTeamResource(UserTeam::find($id));
        if($userTeam) {
          return response([
            'status' => 'success',
            'data' => $userTeam
          ], 200);
        } else {
          return response([
            'status' => 'error',
            'error' => 'team.notfound',
            'msg' => 'Time não encontrado'
          ], 404);
        }
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
        $userTeam = UserTeam::find($id);
        $userTeam->name = $request->name;
        $userTeam->badge = $request->badge;
        if($userTeam->save()) {
          return response ([
            'status' => 'success',
            'data' => new UserTeamResource($userTeam)
          ], 200);
        } else {
          return response ([
            'status' => 'error',
            'error' => 'update.error',
            'msg' => 'Ocorreu um erro ao alterar o time'
          ], 500)
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = UserTeam::find($id);
        if($team->user_id == Auth::user()->id) {
          if($team->destroy()) {
            return response ([
              'status' => 'success'
            ], 200);
          } else {
            return response ([
              'status' => 'error'
            ], 500);
          }
        } else {
          return response ([
            'status' => 'error'
          ], 400);
        }
    }
}
