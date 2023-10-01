<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
{{-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet"> --}}
<title>Receipt Template</title>
<style type="text/css">
    /* @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap'); */
    /* @import url('https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap'); */
  @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
	body {
		margin: 0;
        font-family: 'Roboto', sans-serif;
    }
	table {
		border-spacing: 0;
	}
	td {
		padding: 0;
	}
	img {
		border: 0;
	}

    .wrapper{
        position: relative;
        width: 100%;
        table-layout: fixed;
        background-color:#ffedd65b;
        padding-bottom: 60px;
        padding-top:70px;
        z-index: 2;
    }

    .main{
        /* padding-top: 20px; */
        width: 100%;
        max-width: 600px;
        background-color:#fff;
        /* font-family: sans-serif; */
        color: #4a4a4a;
        box-shadow: 3px 4px 4px -1px rgba(163, 163, 163, 0.199);
        border: 1px solid #e9ecef;
        z-index: 10;
    }

    .main::after{
        content: "";
        width: 100%;
        height: 20%;
        background: #faa507cb;
        position: absolute;
        top: 0;
        left: 0;
        z-index: -1;

    }

    .social{
        margin-right: 20px;
    }

    .social-media{
        padding: 12px 0 12px 200px;
    }
    .two-columns{
        font-size: 0;
        text-align: left;
        padding-left: 20px;
    }

    .two-columns .column{
        width: 100%;
        max-width: 300px;
        vertical-align: middle;
    }

</style>
</head>
<body>

	<center class="wrapper">

		<table class="main" width="100%">

<!-- BORDER -->
<tr>
    <td height="8" style="background-color:#fff"></td>
</tr>

<!-- LOGO & SOCIAL MEDIA SECTION -->
    <tr>
        <td style="padding: 0px 0 4px;">
            <table width="100%">
                <tr>
                    <td class="two-columns">
                        <table class="column">
                            <tr>
                                <td style="padding:0 0 10px">
                                    <a href="#">
                                        <img src="" alt="stacks-hub-logo" title="Logo">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td height="2" style="background-color:#f48c06"></td>
    </tr>

<!-- TITLE, TEXT & BUTTON -->
<tr>
    <td style="padding: 5px;">
        <table width="100%">
            <tr>
                <td style="padding: 0 22px">
                    <p style="font-size:20px; text-align:left;font-weight:500; color:#4a4a4a;margin-bottom:10px ">Hello {{ $data['name'] }} !</p>
                    <p style="font-size:14px; text-align:left; font-weight:500; color:#4a4a4a; line-height:1.6rem; ">A new Donation for your campaign has been confirmed.</p>
                    <p style="font-size:14px; text-align:left; font-weight:500; color:#4a4a4a; line-height:1.6rem; ">Below is the full details of the donation.</p>
                </td>
            </tr>
        </table>
    </td>

</tr>
<tr>
    <td height="1" style="background-color:#e9ecef"></td>
</tr>

<tr>
    <td style="padding: 5px;">
        <table width="100%">
            <tr>
                <td style="padding: 0 22px">
                    <p style="font-size:14px; font-weight:500; color:#4a4a4a; text-align:left;"><span style="font-weight:500; font-size:15px;">Donor Name:</span> <span style="color:#4a4a4a; font-weight:700;font-size:15px;">{{$data['donor_name']}}</span></p>
                    <p style="font-size:14px; font-weight:500; color:#4a4a4a; text-align:left;"><span style="font-weight:500; font-size:15px;">Donor Email:</span> {{$data['email']}}</p>
                    <p style="font-size:14px; font-weight:500; color:#4a4a4a; text-align:left;"><span style="font-weight:500; font-size:15px;">Donation Date:</span> {{$data['date']}}</p>
                </td>
                <td style="padding: 0 22px">
                    <p style="font-size:14px; font-weight:500; color:#4a4a4a; text-align:left;"><span style="font-weight:500; font-size:15px;">Campaign Title:</span> {{$data['title']}}</p>
                    <p style="font-size:14px; font-weight:500; color:#4a4a4a; text-align:left;"><span style="font-weight:500; font-size:15px;">Amount Donated:</span> GHS.{{$data['amount']}}</p>
                    <p style="font-size:14px; font-weight:500; color:#4a4a4a; text-align:left;"><span style="font-weight:500; font-size:15px;">Payment Method:</span> <span style="color:#4a4a4a; font-weight:700;font-size:15px;">{{$data['payment_method']}}</span></p>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td height="1" style="background-color:#e9ecef"></td>
</tr>


<tr>
    <td style="background-color:#f8f7f5ec">
        <table width="100%" style="text-align: center; padding:10px 0;">
            <tr>
                <td style="padding:0 22px 0px">
                    <a style="text-decoration:none; color:#4a4a4a" href="#"><h4 style="font-size: 14px">Thank you for using <span style="color:#4a4a4a; fontw-weight:700;text-tranform:uppercase">stacks-investment-hub</span></h4>
                    </a>
                    <p style="color:#e85d04; font-weight:600; font-size:14px"></p>
                </td>
            </tr>
            <tr>
                <td style="text-align: center">
                    <a href=" https://www.facebook.com/DuvaiDeals"><img src="https://drive.google.com/uc?export=view&id=15PBC7_EpwLpal11RpL27cAtQf74CjFWs" alt="" class="social"></a>
                    <a href=" https://www.instagram.com/duvai_deals/"><img src="https://drive.google.com/uc?export=view&id=1lzLTVyq0ArmS3E7GNnUN90RW0rkxYBJ7" alt="" class="social"></a>
                </td>
            </tr>
        </table>
    </td>
</tr>

		</table> <!-- End Main Class -->

	</center> <!-- End Wrapper -->

</body>
</html>
