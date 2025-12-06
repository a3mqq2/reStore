<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title') - re-store </title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link href="{{asset('/assets/css/style-a4.css')}}" rel="stylesheet" type="text/css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <style>
        @page {
            size: "{{isset($paper_size) ? $paper_size : 'A4'}}"
        }

        
    </style>
    @if(isset($landscape))
    <style>
        @media print {
            @page {
                size: landscape
            }
        }
    </style>

    @endif

    <style>
        .A4 .sheet {
            width: 210mm;
            height: auto !important;
        }
    </style>
</head>

<body class="{{isset($paper_size) ? $paper_size : 'A4'}}   @if(isset($landscape)) landscape @endif ">
    @if(!isset($blank))

    <section class="sheet padding-10mm" dir="rtl" id="invoice">
        <div class="text-center">
            <img src="{{asset('/assets/images/logo-dark.svg')}}" width="200" />
        </div>

        <hr>
        @yield("content")
    </section>
    @else
    @yield("content")
    @endif


</body>



@if(request('download'))
<script>
    const invoice = this.document.getElementById("invoice");

    var opt = {
        margin: 0,
        pagesplit: true,
        filename: 'file.pdf',
        image: {
            type: 'svg',
            quality: 1
        },
        html2canvas: {
            scale: 1
        },
        jsPDF: {
            unit: 'cm',
            format: 'A4',
            orientation: 'portrait'
        }
    };
    html2pdf().from(invoice).set(opt).then(function(pdf) {
        setTimeout(() => {
            location.href = '/';
        }, 1000);
    }).save();
</script>

@else
<script>
    setTimeout(() => {
        window.print()
    }, 400);
</script>
@endif


</html>