@extends('layouts.app')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 main-page">
            <div class="panel panel-default">
                <div class="panel-heading">Projects <a href="javascript:void(0);" id="export"><i class="fa fa-download" aria-hidden="true" style="float:right"></i></a></div>
                      <table class="table table-striped" id="myTable">
                        <thead>
                          <tr>
                            <th width=120 class="month" style="background-image: none !important;">
                              @if(Request::query('page') >= 1)
                                <a href="?page={{ Request::query('page') - 1 }}"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
                              @else
                                <a href="/projects"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
                              @endif
                              <a href="?page={{ Request::query('page') + 1 }}" style="float:right;"><i class="fa fa-chevron-right" aria-hidden="true"></a></i>
                            </th>
                            <th>Ticket-added slides</th>
                            <th>Tickets</th>
                            <th>Members</th>
                            <th>Uploaded slides</th>
                            <th>Email [teammember-rowid] Ticket added accounts(to assume company name)</th>
                            <th>Folder title [count of ticket-added slides] All Folders </th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($projects as $project)
                          <tr>
                            <td>{{ substr($project->title, 0, 20) }} [{{ $project->project_rowid }}]</td>
                            <td>
                              <?php  $ticketSlideCount = $project->ticketSlideCounts(); ?>
                              {{$ticketSlideCount }}
                            </td>
                            <td>{{ $project->ticketCounts() }}</td>
                            <td>
                              <?php  $members = $project->members(); ?>
                              {{ count($members) }}</td>
                            <td>{{ $project->uploadedFileCounts() }}</td>
                            <td>
                              @if(count($members) > 0)
                                @foreach($members as $member)
                                  ~~ {{ $member->email }} [{{ $member->teammember_rowid }}],
                                @endforeach
                              @endif
                            </td>
                            <td>
                              <?php  $folders = $project->qaFolders(); ?>
                              @if(count($folders) > 0)
                                @foreach($folders as $folder)
                                  {{ $folder->title }} [{{ $folder->completed + $folder->incompleted }} ],
                                @endforeach
                              @endif
                            </td>
                          </tr>
                          @endforeach
                        </tbody>
                      </table>
              </div>
        </div>
    </div>
</div>
@endsection
