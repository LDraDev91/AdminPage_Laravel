@extends('layouts.app')

@section('content')

<div class="">
    <div class="row">
        <div class="col-md-12 main-page">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard | <a href="javascript:void(0);" id="export"><i class="fa fa-download fa-lg" aria-hidden="true" ></i></a></div>
                      <table class="table table-striped table-fixed" id="">
                        <thead>
                          <tr>
                            <th width=120 class="month"><a href="javascript:void(0);" class="month_select" data-type="desc"><i class="fa fa-chevron-left" aria-hidden="true"></i></a> {{ $range }} <a href="javascript:void(0);" class="month_select" data-type="asc"><i class="fa fa-chevron-right" aria-hidden="true"></a></i></th>
                            <th  width=120 >New members</th>
                            <th width=150>New ticket-added projects</th>
                            <th  width=120 >New ticket-added slides</th>
                            <th  width=150 >New added tickets</th>
                            <th style="width:331px; word-wrap:break-word;">Email [teammember-rowid] Ticket added accounts(to assume company name)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            $newMemberSum = 0;
                            $newTicketProjectSum = 0;
                            $newTicketAddedSlideSum = 0;
                            $newTicketSum = 0;
                            $emailSum = 0;
							$curdate = "";
							$exists = 0;
                          ?>
                          @foreach($projects as $project) 
						  <?php  //echo '<pre>';print_r($projects);die;?>
                          <tr>
                            <td>
							@if (date('Y-m-d', strtotime($project->regdate)) != $curdate)
								{{ date('Y-m-d', strtotime($project->regdate)) }}
								<?php $curdate = date('Y-m-d', strtotime($project->regdate)); $exists = 0;?>
								@else 
								<?php $exists = 1; ?>
							@endif
							</td>
                            <td>
                              <?php
							 
                                $newMember = $project->newMembers($project->regdate);
                                //echo '<pre>';print_r($newMember);die;
                              ?>
							  @if ($exists == 0)
                              {{ $newMember }}
							  <?php $newMemberSum = $newMemberSum +  $newMember; ?>
							  @endif
							</td>
                            <td style="font-size: 9pt;">
                              <?php
                                  $newAddedProjects = $project->ticketaddedProjects($project->regdate);
								   
                                ?>
								 @if ($exists == 0)
								{{ count($newAddedProjects) }}
								<?php   $newTicketProjectSum = $newTicketProjectSum + count($newAddedProjects); ?>
							   @endif
                              <br>

                                  <a href="{{url('/')}}/{{ $project->project_rowid }}">{{ $project->title}} [{{ $project->project_rowid }}]</a>
								   
                                
                            </td>
                            <td style="font-size: 9pt;">
                              <?php
                                $newTicketAddedSlides = $project->newTicketAddedSlidesCountByProjectId();
                                $newTicketAddedSlideSum = $newTicketAddedSlideSum + $newTicketAddedSlides;
                              ?>
                              {{ $newTicketAddedSlides }}</td>
                            <td>
                              <?php
                                $tickets = $project->ticketAddedProject($project->regdate);
                                $newTicketAddedCounts = count($tickets);
                                $newTicketSum = $newTicketSum + $newTicketAddedCounts;
                                $emailSum =  $emailSum + count($tickets);
                              ?>
                              {{ $newTicketAddedCounts }}
                            </td>
                            <td style="font-size: 9pt;">
                              
                              @if(count($tickets) > 0)
                                @foreach($tickets as $ticket)
                                  ~~ {{ $ticket->email }} [{{ $ticket->teammember_rowid }}],
                                @endforeach
                              @endif
                            </td>
                          </tr>
                          @endforeach
                          <tr>
                            <td>SUM</td>
                            <td>{{ $newMemberSum }}</td>
                            <td>{{ $newTicketProjectSum }}</td>
                            <td>{{ $newTicketAddedSlideSum }}</td>
                            <td>{{ $newTicketSum }}</td>
                            <td>{{ $emailSum }}</td>
                          </tr>
                        </tbody>
                      </table>
              </div>
             
        </div>
    </div>
</div>
 
@endsection
