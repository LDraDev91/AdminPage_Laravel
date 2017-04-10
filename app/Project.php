<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Project extends Model
{
    protected $table = 'project';
    protected $guarded = array();
    protected $primaryKey = 'project_rowid';

    public function newMembers($date) {
      $date = date('Y-m-d', strtotime($date));
      $start_time = $date. ' 00:00:00.00';
      $end_time  = $date. ' 23:59:59.99';
      return DB::table('member')
              ->whereBetween('regdate', [$start_time, $end_time])->count();
    }

    public function ticketaddedProjects($date) {
      $date = date('Y-m-d', strtotime($date));
      $start_time = $date. ' 00:00:00.00';
      $end_time  = $date. ' 23:59:59.99';
      return DB::table('ticket')
              ->join('teammember', 'teammember.teammember_rowid', '=', 'ticket.teammember_rowid')
              ->join('project', 'project.project_rowid', '=', 'teammember.project_rowid')
              ->whereBetween('ticket.regdate', [$start_time, $end_time])
              ->select('project.project_rowid', 'project.title')
              ->distinct()
              ->get();
    }

    public function newTicketAddedCounts($date)
    {
      $date = date('Y-m-d', strtotime($date));
      $start_time = $date. ' 00:00:00.00';
      $end_time  = $date. ' 23:59:59.99';
      return DB::table('ticket')
              ->whereBetween('regdate', [$start_time, $end_time])->count();
    }

    public function ticketAdded($date) {
      $date = date('Y-m-d', strtotime($date));
      $start_time = $date. ' 00:00:00.00';
      $end_time  = $date. ' 23:59:59.99';
      return DB::table('ticket')
              ->join('teammember', 'teammember.teammember_rowid', '=', 'ticket.teammember_rowid')
              ->whereBetween('ticket.regdate', [$start_time, $end_time])
              ->select('teammember.teammember_rowid', 'teammember.email')
              ->get();
    }

	public function ticketAddedProject($date) {
      $date = date('Y-m-d', strtotime($date));
      $start_time = $date. ' 00:00:00.00';
      $end_time  = $date. ' 23:59:59.99';
      return DB::table('ticket')
              ->join('teammember', 'teammember.teammember_rowid', '=', 'ticket.teammember_rowid')
              ->whereBetween('ticket.regdate', [$start_time, $end_time])
				->where('project_rowid', $this->project_rowid)
              ->select('teammember.teammember_rowid', 'teammember.email')
              ->get();
    }

    public function newTicketAddedSlides($date) {
      $date = date('Y-m-d', strtotime($date));
      $start_time = $date. ' 00:00:00.00';
      $end_time  = $date. ' 23:59:59.99';
      return DB::table('qaslide')
              ->whereBetween('regdate', [$start_time, $end_time])
              ->where('num', 1)
              ->count();
    }

    public function newTicketAddedSlidesCountByProjectId()
    {
      return DB::table('ticket')
              ->join('teammember', 'teammember.teammember_rowid', '=', 'ticket.teammember_rowid')
              ->where('teammember.project_rowid', $this->project_rowid)
              ->where('ticket.num', 1)
              ->count();
    }

    public function ticketCounts()
    {
      return DB::table('ticket')
                ->join('teammember', 'teammember.teammember_rowid', '=', 'ticket.teammember_rowid')
                ->where('teammember.project_rowid', $this->project_rowid)
                ->count();
    }

    public function ticketSlideCounts()
    {
      return DB::table('ticket')
                ->join('teammember', 'teammember.teammember_rowid', '=', 'ticket.teammember_rowid')
                ->join('qaslide',  'qaslide.qaslide_rowid', '=', 'ticket.qaslide_rowid')
                ->where('teammember.project_rowid', $this->project_rowid)
                ->where('qaslide.num', 1)
                ->count();
    }

    public function uploadedFileCounts()
    {
      return DB::table('ticket')
                ->join('teammember', 'teammember.teammember_rowid', '=', 'ticket.teammember_rowid')
                ->join('qaslide',  'qaslide.qaslide_rowid', '=', 'ticket.qaslide_rowid')
                ->join('upfile', 'upfile.upfile_rowid', '=', 'qaslide.upfile_rowid')
                ->join('upfilecatext', 'upfilecatext.upfilecatext_rowid' ,'=', 'upfile.upfilecatext_rowid')
                ->where('upfilecatext.upfilecat_rowid', 2)
                ->where('teammember.project_rowid', $this->project_rowid)
                ->count();
    }

    public function members()
    {
      return DB::table('teammember')
                ->where('project_rowid', $this->project_rowid)
                ->select('teammember_rowid', 'email')
                ->get();
    }

    public function qaFolders()
    {

      return DB::table('ticket')
                ->join('teammember', 'teammember.teammember_rowid', '=', 'ticket.teammember_rowid')
                ->join('qaslide',  'qaslide.qaslide_rowid', '=', 'ticket.qaslide_rowid')
                ->join('qahierarchy',  'qahierarchy.qahierarchy_rowid', '=', 'qaslide.qahierarchy_rowid')
                ->select('qahierarchy.title', 'qahierarchy.completed', 'qahierarchy.incompleted')
                ->where('teammember.project_rowid', $this->project_rowid)
                ->where('qaslide.incompleted', '!=', 0)
                ->where('qaslide.completed', '!=', 0)
                ->distinct()
                ->get();
    }
}
