<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>1 Labels</title>
</head>
<body>
    <?php
        $settings->labels_width = $settings->labels_width - $settings->labels_display_sgutter;
        $settings->labels_height = $settings->labels_height - $settings->labels_display_bgutter;
        // Leave space on bottom for 1D barcode if necessary
        $qr_size = ($settings->alt_barcode_enabled=='1') && ($settings->alt_barcode!='') ? $settings->labels_height - 0.3 : $settings->labels_height - 0.3;
        // Leave space on left for QR code if necessary
        $qr_txt_size = ($settings->qr_code=='1' ? $settings->labels_width - $qr_size - 0.1: $settings->labels_width); //old 0.1
    ?>

    <style>
        body {
            font-family: arial, helvetica, sans-serif;
            width: {{ $settings->labels_pagewidth }}in;
            height: {{ $settings->labels_pageheight }}in;
            margin: {{ $settings->labels_pmargin_top }}in {{ $settings->labels_pmargin_right }}in {{ $settings->labels_pmargin_bottom }}in {{ $settings->labels_pmargin_left }}in;
            font-size: {{ $settings->labels_fontsize }}pt;
        }
        .label {
            width: {{ $settings->labels_width }}in;
            height: {{ $settings->labels_height }}in;
            padding: 0in;
            margin-right: {{ $settings->labels_display_sgutter }}in; /* the gutter */
            margin-bottom: {{ $settings->labels_display_bgutter }}in;
            display: inline-block;
            overflow: hidden;
        }
        .page-break  {
            page-break-after:always;
        }
        div.qr_img {
            width: 70px;
            height: 70px;
            float: left;
            display: inline-block;
            padding-right: .04in;
	    padding-bottom: .02in;
        }
        img.qr_img {
            width: 100%;
            height: 100%; 
        }
        img.barcode {
            display: flex;
	    float: right;
            margin-left: auto;
            margin-right: auto;
	    height: 25px;
        }
        .qr_text {
            width: {{ $qr_txt_size }}in;
           
            font-family: arial, helvetica, sans-serif;
            padding-right: .01in;
            overflow: hidden !important;
            display: inline-block;
            word-wrap: break-word;
            word-break: break-all;
	    text-align: right;
        }
        div.barcode_container {
		display: flex;
  		margin: auto;            
		
            width: 90%; 
            height: 30px;
        }
    
        .company-name {
            font-weight: bold;
            font-size: {{ $settings->labels_fontsize + 1}}pt;
        }
        .asset-tag {
            font-weight: bold;
	    text-align: right;
            font-size: {{ $settings->labels_fontsize + 3}}pt; /* formally labels_fontsize * 2 */
        }
	.SN-tag {
            text-align: right;
            font-size: {{ $settings->labels_fontsize + -1}}pt; /* formally labels_fontsize * 2 */
        }

        .next-padding {
            margin: {{ $settings->labels_pmargin_top }}in {{ $settings->labels_pmargin_right }}in {{ $settings->labels_pmargin_bottom }}in {{ $settings->labels_pmargin_left }}in;
        }
        @media print {
            .noprint {
                display: none !important;
            }
            .next-padding {
                margin: {{ $settings->labels_pmargin_top }}in {{ $settings->labels_pmargin_right }}in {{ $settings->labels_pmargin_bottom }}in {{ $settings->labels_pmargin_left }}in;
                font-size: 0;
            }
        }
        @media screen {
            .label {
                outline: .02in black solid; /* outline doesn't occupy space like border does */
            }
            .noprint {
                font-size: 13px;
                padding-bottom: 15px;
            }
        }
            @if ($snipeSettings->custom_css)
                {{ $snipeSettings->show_custom_css() }}
            @endif
    </style>

    @foreach ($assets as $asset)
	<?php $count++; ?>
    <div class="label"> 
	@if ($settings->qr_code=='1')
        	<div class="qr_img"><img src="./{{ $asset->id }}/qr_code" class="qr_img"></div>
        @endif

        @if (($settings->labels_display_company_name=='1') && ($asset->company))
        <div class="pull-left company-name"><strong>{{ $asset->company->name }}</strong></div>
        @endif

            <div class="qr_text">
            @if ($settings->qr_text!='')
            <div class="pull-left"><strong>{{ $settings->qr_text }}</strong></div>
            @endif   

            
	    @if (($settings->labels_display_tag=='1') && ($asset->asset_tag!=''))
		<div class="pull-left asset-tag">{{ $asset->asset_tag }}</div>
            @endif

        
            @if (($settings->labels_display_name=='1') && ($asset->name!=''))
            <div class="pull-left"><strong>{{ $asset->name }}</strong></div>
            @endif
            
            @if (($settings->labels_display_serial=='1') && ($asset->serial!=''))
            <div class="pull-left SN-tag"><strong>S/N:</strong> {{ $asset->serial }}</div>
            @endif

            @if (($settings->labels_display_model=='1') && ($asset->model->name!=''))
            <div class="pull-left"><strong>MDL:</strong> {{ $asset->model->name }}<br>{{ $asset->model->model_number }}</div>
            @endif

        </div>
	@if ((($settings->alt_barcode_enabled=='1') && $settings->alt_barcode!=''))
            <div class="barcode_container">
                <img src="./{{ $asset->id }}/barcode" class="barcode">
            </div>
            @endif

    </div>

    @if ($count % $settings->labels_per_page == 0)
    <div class="page-break"></div>
    <div class="next-padding">&nbsp;</div>
    @endif

    @endforeach
</body>
</html>
