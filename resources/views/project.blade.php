@extends('layouts.app')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12 main-page">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $project->title }} [{{ $project->project_rowid}}]
                 | <a href="javascript:void(0);" id="export">
                    <i class="fa fa-download fa-lg" aria-hidden="true" style=""></i>
                  </a>
                </div>
                <div class=" ">
                      <table class="table table-striped" id="myTablededf">
                        <thead>
                          <tr>
                            <th width="150" class="month">
                              <a href="javascript:void(0);" class="month_select" data-type="desc">
                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                              </a> {{ $range }}
                              <a href="javascript:void(0);" class="month_select" data-type="asc">
                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                              </a>
                            </th>
                            <th  width="75" colspan="3" scope="colgroup">New ticket-added slides / New tickets</th>
                            @if(count($members) > 0)
                              @foreach($members as $member)
                                <th>{{ $member->email }} [{{ $member->teammember_rowid }}] </th>
                              @endforeach
                            @endif
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $slideSum = 0;
                            $ticketSum = 0;
                            $sum1 = 0;
                            $sum2 = 0;
                            $dySums = [];
                          ?>
                          @foreach($projects as $project)
                          <tr>
                            <td>{{ date('Y-m-d', strtotime($project->regdate)) }}</td>
                            <td   style="font-size: 9pt;">
                              <?php
                                $newTicketAddedSlidesCountByProjectId = $project->newTicketAddedSlidesCountByProjectId();
                                $slideSum = $slideSum +  $newTicketAddedSlidesCountByProjectId;
                              ?>
                              {{ $newTicketAddedSlidesCountByProjectId }}
                            </td>
                            <td width="10" class="">/</td>
                            <td  style="font-size: 9pt;">
                              <?php
                                $ticketCounts = $project->ticketCounts();
                                $ticketSum = $ticketSum +  $ticketCounts;
                              ?>
                              {{ $ticketCounts }}
                            </td>

                            <?php
                              for($i = 0; $i <= count($members); $i++) {
                                if(isset($members[$i])){
                                  echo '<td>'.$members[$i]->ticketSlideCount.' / '.$members[$i]->ticketCount.'</td>';
                                  $chk = '<td>'.$members[$i]->ticketSlideCount .' / '. $members[$i]->ticketCount .'</td>';
                                  array_push($dySums, $chk);
                                }
                              }
                            ?>
                          </tr>
                          @endforeach
                          <tr>
                            <td>SUM</td>
                            <td>{{ $slideSum }}</td>
                            <td>/</td>
                            <td>{{ $ticketSum }}</td>
                            <?php
                              for($i = 0; $i < count($dySums); $i++) {
                                echo $dySums[$i];
                              }
                            ?>
                          </tr>
                        </tbody>
                      </table>
                  </div>
              </div>
              {!! $projects->appends(['range'=>$range])->render() !!}
        </div>
    </div>
</div>
@endsection
