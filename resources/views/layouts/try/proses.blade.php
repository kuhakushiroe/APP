@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <a href="/" class="btn btn-sm btn-secondary">
            <i class="bi bi-caret-left"></i> Back
        </a>
        <div class="row">
            <div class="col-md-12">
                <!-- The time line -->
                <div class="timeline">
                    <!-- timeline time label -->
                    <div class="time-label"><span class="text-bg-danger">10 Feb. 2023</span></div>
                    <!-- /.timeline-label -->
                    <!-- timeline item -->
                    <div>
                        <i class="timeline-icon bi bi-envelope text-bg-primary"></i>
                        <div class="timeline-item">
                            <span class="time"> <i class="bi bi-clock-fill"></i> 12:05 </span>
                            <h3 class="timeline-header">
                                <a href="#">Support Team</a> sent you an email
                            </h3>
                            <div class="timeline-body">
                                Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning
                                heekya handango imeem plugg dopplr jibjab, movity jajah plickers sifteo
                                edmodo ifttt zimbra. Babblely odeo kaboodle quora plaxo ideeli hulu weebly
                                balihoo...
                            </div>
                            <div class="timeline-footer">
                                <a class="btn btn-primary btn-sm">Read more</a>
                                <a class="btn btn-danger btn-sm">Delete</a>
                            </div>
                        </div>
                    </div>
                    <!-- END timeline item -->
                    <!-- timeline item -->
                    <div>
                        <i class="timeline-icon bi bi-person text-bg-success"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="bi bi-clock-fill"></i> 5 mins ago </span>
                            <h3 class="timeline-header no-border">
                                <a href="#">Sarah Young</a> accepted your friend request
                            </h3>
                        </div>
                    </div>
                    <div><i class="timeline-icon bi bi-clock-fill text-bg-secondary"></i></div>
                    <!-- END timeline item -->
                </div>
            </div>
            <!-- /.col -->
        </div>
    </div>
@endsection
