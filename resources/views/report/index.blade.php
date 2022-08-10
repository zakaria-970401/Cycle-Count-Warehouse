@extends('layouts.base')

@section('judul', 'Report')
@section('judul_konten', 'Halaman Report Cycle Count')


@section('content')
    <style type="text/css">
        .hide {
            display: none;
        }
    </style>
    <div class="container-xxl" id="kt_content_container">
        <div class="row g-5 g-xl-8">
            <div class="col-xxl-12">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group">
                              <select class="form-control" onchange="cariData(this.value)" name="" id="">
                                <option value="" selected disabled>TAHUN {{date('Y')}}</option>
                                @for ($i = 22; $i <= 50; $i++)
                                    <option value="20{{$i}}">20{{$i}}</option>
                                @endfor
                              </select>
                            </div>
                            <div class="col-sm-6 mt-4">
                                <div id="performa_user">
                                </div>
                            </div>
                            <div class="col-sm-6 mt-4">
                                <div id="reason">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script type="text/javascript">

    Highcharts.chart('performa_user', {
    chart: {
        type: 'column'
    },
    title: {
        text: "Perhitungan Cycle Count User Gudang TAHUN <b class='tahunValue'>{{date('Y')}}</b>"
    },
    subtitle: {
        text: 'Perhitungan Berdasarkan Data OK'
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Perhitungan Cycle Count'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y} OK</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0,
        }
    },
    series:{!! $series !!}
});

    Highcharts.chart('reason', {
    chart: {
        type: 'column'
    },
    title: {
        text: "Akumulasi Reason Cycle Count TAHUN <b class='tahunValue'>{{date('Y')}}</b>"
    },
    credits: {
        enabled: false
    },
    xAxis: {
        categories: [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Akumulasi Reason Cycle Count'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y} DATA</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0,
            colorByPoint: true
        }
    },
    series:{!! $series_reason !!}
});

function cariData(tahun){
    $.ajax({
        url: "{{url('cycle-count/report/searchByTahun')}}/"+tahun,
        type: "GET",
        dataType: "JSON",
        success: function(response){
            if(response.status == 'not found'){
                toastr.error('Data tidak ditemukan');
                $('#performa_user').html('');
                $('#reason').html('');
            }else{
                $('#performa_user').html('');
                $('#reason').html('');

                Highcharts.chart('performa_user', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: "Perhitungan Cycle Count User Gudang TAHUN <b class='tahunValue'>"+tahun+"</b>"
                    },
                    subtitle: {
                        text: 'Perhitungan Berdasarkan Data OK'
                    },
                    credits: {
                        enabled: false
                    },
                    xAxis: {
                        categories: [
                            'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember'
                        ],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Perhitungan Cycle Count'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y} OK</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                        }
                    },
                    series:{!! $series !!}
                });

                    Highcharts.chart('reason', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: "Akumulasi Reason Cycle Count TAHUN <b class='tahunValue'>"+tahun+"</b>"
                    },
                    credits: {
                        enabled: false
                    },
                    xAxis: {
                        categories: [
                            'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember'
                        ],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Akumulasi Reason Cycle Count'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y} DATA</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            colorByPoint: true
                        }
                    },
                    series:{!! $series_reason !!}
                });
            }
          
        },
        error: function(error){
            toastr.error('Internal Server Error, Please Try Again');
        }
    });
}
</script>
@endsection
