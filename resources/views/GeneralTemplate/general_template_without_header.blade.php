<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <title>{{ $template->name }}</title>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
        <style>
            .header, .header-space
            {
              height: 162px!important;
            }

            .footer, .footer-space
            {
              height: 217px!important;
            }

            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
            }
            .footer {
                position: fixed;
                bottom: 0; 
                left: 0;
                right: 0;
            }
            html, body {
                width: 210mm;
                height: 297mm;
              }
            @page {
              size: A4;
              margin: 0;
              margin-top: 0;
              margin-bottom: 0;
            }
            @media print {
                html, body {
                    width: 210mm;
                    height: 297mm;
                }
            }
        </style>
    </head>
<body onload="print()">

    <table>
        <thead>
            <tr>
                <td>
                    <div class="header-space">&nbsp;</div>
                </td>
            </tr>
        </thead> 
        <tbody>
            <tr>
                <td>
                    <div class="content" style="padding-left: 50px!important; padding-right: 50px!important">
                        <div class="row" style="margin-bottom: -2em;">
                            <div class="col-12">
                                <table style="white-space: nowrap;font-weight: bolder;font-size: 16px;">
                                    <tr>
                                        <th>
                                            {!! $template->template  !!}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>


        <tfoot>
            <tr>
                <td>
                    <div class="footer-space">&nbsp;</div>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="header"></div>
    <div class="footer"></div>
    

</div>
  
</body>
</html>