<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project, DB, App\Teamember;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $range =  date('Y-m');
        if($request->query('range') && $request->query('range') != '') {
          $range = $request->query('range');
        }
        $projects = $this->projects($range);
        return view('home')->with('projects', $projects)->with('range', $range);
    }

    public function projects($date)
    {
      $timestamp = strtotime($date);
      $first_day = date('Y-m-01 00:00:00', $timestamp);
      $last_day  = date('Y-m-t 23:59:59', $timestamp);
         return Project::whereBetween('ticket.regdate', [$first_day, $last_day])
			->rightJoin('teammember', 'project.project_rowid', '=', 'teammember.project_rowid')
			->rightJoin('ticket', 'teammember.teammember_rowid', '=', 'ticket.teammember_rowid')
			 //->groupBy('project.project_rowid','project.title')
             ->orderBy('ticket.regdate', 'DESC')
              //->distinct()
			
             // ->paginate(100);
			  ->get();   
		/* return DB::table('ticket')
              ->join('teammember', 'teammember.teammember_rowid', '=', 'ticket.teammember_rowid')
              ->join('project', 'project.project_rowid', '=', 'teammember.project_rowid')
              ->whereBetween('ticket.regdate', [$first_day, $last_day])
              ->select('project.project_rowid', 'project.title', 'project.regdate')
              ->distinct()
              ->get(); */
    }

    public function project(Request $request, $id)
    {
      $range =  date('Y-m');
      if($request->query('range') && $request->query('range') != '') {
        $range = $request->query('range');
      }
      $projects = $this->getProjectDetails($range, $id);
      $project = Project::find($id);
      $members = $this->getMembersWithSlideTicketCount($range, $id);
	  
      return view('project')->with('projects', $projects)->with('range', $range)->with('project', $project)->with('members', $members);
    }

    private function getProjectDetails($date, $id)
    {
      $timestamp = strtotime($date);
      $first_day = date('Y-m-01 00:00:00', $timestamp);
      $current_day  = date('Y-m-d 11:59:59');
      return Project::whereBetween('regdate', [$first_day, $current_day])
              ->where('project_rowid', $id)
              ->orderBy('regdate', 'DESC')
              ->paginate(100);
    }

    private function getMembersWithSlideTicketCount($date, $id)
    {
      $timestamp = strtotime($date);
      $first_day = date('Y-m-01 00:00:00', $timestamp);
      $current_day  = date('Y-m-d 11:59:59');
      $sql = "select distinct(teammember.email), teammember.project_rowid, teammember.teammember_rowid, (SELECT COUNT(*) FROM ticket WHERE teammember_rowid = teammember.teammember_rowid AND num=1) AS ticketSlideCount, (SELECT COUNT(*) FROM ticket WHERE teammember_rowid = teammember.teammember_rowid) AS ticketCount FROM `teammember` LEFT JOIN ticket ON ticket.teammember_rowid = teammember.teammember_rowid WHERE teammember.project_rowid='".$id."' AND teammember.regdate BETWEEN '".$first_day."' AND '".$current_day."' order by ticketSlideCount DESC";
      $members = DB::select($sql);
      return $members;
    }

}
