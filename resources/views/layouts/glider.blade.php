<!DOCTYPE html>
<html lang="en">
    <head>
       <!--- Required meta tags --->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Tournament Kings</title>
  	    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />
	      <!--- bootstrap.min.css --->
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="css/landing.css">
        {{-- <link rel="stylesheet" href="assets/css/glider.css"> --}}
    </head>
    <body>
        <!-- SVG icon sprite -->
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none">

            <!-- Mail -->
            <symbol id="icon-email" viewBox="0 0 32 32">
                <title>envelope</title>
                <path fill="#F7BE00" d="M28.8 3.2c1.767 0 3.2 1.433 3.2 3.2v0 19.2c0 1.767-1.433 3.2-3.2 3.2v0h-25.6c-1.767 0-3.2-1.433-3.2-3.2v0-19.2c0-1.76 1.44-3.2 3.2-3.2h25.6zM21.808 17.76l10.192 7.84v-3.2l-8.192-6.24 8.192-6.56v-3.2l-16 12.8-16-12.8v3.2l8.192 6.56-8.192 6.24v3.2l10.192-7.84 5.808 4.64 5.808-4.64z"></path>
            </symbol>

            <!-- Twitter -->
            <symbol id="icon-twitter" viewBox="0 0 30 24">
            <path fill="#F7BE00" d="M75.4838299,7.2085 C75.4965843,7.45825 75.5014898,7.71185714 75.5014898,7.96160714 C75.5014898,15.6604643 69.5412791,24.5347857 58.6421875,24.5347857 C55.2936773,24.5347857 52.1806323,23.5705 49.5581395,21.91675 C50.0231831,21.97075 50.4941134,21.9987143 50.9709302,21.9987143 C53.7494186,21.9987143 56.303234,21.0672143 58.3301962,19.507 C55.7381177,19.4587857 53.5482922,17.7770714 52.7948038,15.4627857 C53.1558503,15.5293214 53.527689,15.565 53.9093387,15.565 C54.4509084,15.565 54.9748183,15.4955714 55.4712573,15.3625 C52.7604651,14.8253929 50.7178052,12.4725357 50.7178052,9.64910714 L50.7178052,9.57678571 C51.5405879,10.0269099 52.4609339,10.2771871 53.4021076,10.30675 C51.8117369,9.2605 50.7648983,7.48042857 50.7648983,5.45735714 C50.7648983,4.39085714 51.0572674,3.38992857 51.568423,2.52882143 C54.4901526,6.05425 58.8580305,8.37142857 63.7831759,8.61539286 C63.6814331,8.17955661 63.6304154,7.73376762 63.6311047,7.28660714 C63.6311047,4.07167857 66.2820494,1.46521429 69.5550145,1.46521429 C71.2601744,1.46521429 72.7995276,2.17010714 73.881686,3.30314286 C75.2297238,3.04085714 76.4982922,2.55678571 77.6442224,1.8895 C77.2017442,3.24914286 76.2608648,4.39085714 75.0384084,5.11117857 C76.2373183,4.96942857 77.3773619,4.65892857 78.4418605,4.19510714 C77.6470894,5.36500249 76.6456022,6.38557198 75.484811,7.2085 L75.4838299,7.2085 Z"
                transform="translate(-49 -1)" />
            </symbol>

            <!-- Facebook -->
            <symbol id="icon-facebook" viewBox="0 0 24 24">
            <path fill="#F7BE00" d="M20.00025,0 L3.99975,0 C1.791,0 0,1.791 0,4.0005 L0,19.9995 C0,22.209 1.79025,24 3.99975,24 L12,24 L12,13.5 L9,13.5 L9,10.5 L12,10.5 L12,8.25 C12,6.17925 13.67925,4.5 15.75,4.5 L19.5,4.5 L19.5,7.5 L15.75,7.5 C15.336,7.5 15,7.836 15,8.25 L15,10.5 L19.125,10.5 L18.375,13.5 L15,13.5 L15,24 L20.00025,24 C22.209,24 24,22.209 24,19.9995 L24,4.0005 C24,1.791 22.20975,0 20.00025,0 Z" />
            </symbol>

            <!-- Twitch -->
            <symbol id="icon-twitch" viewBox="0 0 24 25">
            <path fill="#F7BE00" d="M2.57314142,0 L0.209603659,3.90625 L0.209603659,21.875 L6.51237101,21.875 L6.51237101,25 L9.66375469,25 L12.8151384,21.875 L16.754368,21.875 L23.8449812,14.84375 L23.8449812,0 L2.57314142,0 Z M20.6935976,13.28125 L16.754368,17.1875 L12.8151384,17.1875 L9.66375469,20.3125 L9.66375469,17.1875 L4.93667917,17.1875 L4.93667917,3.125 L20.6935976,3.125 L20.6935976,13.28125 Z M15.1786761,6.25 L17.5422139,6.25 L17.5422139,12.5 L15.1786761,12.5 L15.1786761,6.25 Z M10.4516006,6.25 L12.8151384,6.25 L12.8151384,12.5 L10.4516006,12.5 L10.4516006,6.25 Z" />
            </symbol>
        </svg>
        <header class="site-header" role="banner">
            <!-- Main Menu -->
            <nav class="collapse navbar-collapse" id="main-menu">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                    <li class="cta">
                        <a href="/early-access/register" class="btn-outline-light">Request Early Access</a>
                    </li>
                </ul>
            </nav>
            <nav class="navbar">
              <div class="container-fluid">
                  <div class="navbar-header">
                      <a class= "navbar-brand" href="#">
                          <svg xmlns="http://www.w3.org/2000/svg" class="logo-icon" viewBox="0 0 90 107">
                          <g fill="none" fill-rule="evenodd">
                              <polygon class="ribbon" fill="#000" points="0 0 90 0 90 107 45 82.69 0 107" />
                              <g transform="translate(20 9)">
                              <polygon class="letter" fill="#FFF"
                                  points="27.819 45.02 34.716 33.657 42.277 33.657 34.065 47.301 37.005 50.967 24.726 64.907 28.252 51.701 26.858 49.981 22.916 64.831 17.004 58.456 23.671 33.657 31.163 33.657" />
                              <path class="crown" fill="#F7BE00"
                                  d="M23.9230099,0 L29.7367129,13.8927941 C28.6802376,14.6316912 27.9882772,15.8609559 27.9882772,17.2536765 C27.9882772,19.5133824 29.8094257,21.3448529 32.056396,21.3448529 C34.3028911,21.3448529 36.1245149,19.5133824 36.1245149,17.2536765 C36.1245149,16.5377206 35.9415446,15.8652574 35.6202772,15.2797794 L47.846495,8.97525735 L41.8674059,26.7862132 L23.9230099,26.7862132 L5.97861386,26.7862132 L0,8.97525735 L12.2257426,15.2797794 C11.9044752,15.8652574 11.7219802,16.5377206 11.7219802,17.2536765 C11.7219802,19.5133824 13.5431287,21.3448529 15.7896238,21.3448529 C18.0365941,21.3448529 19.8577426,19.5133824 19.8577426,17.2536765 C19.8577426,15.8609559 19.1657822,14.6316912 18.1093069,13.8927941 L23.9230099,0 Z" />
                              <polygon class="letter" fill="#FFF"
                                  points="22.367 32.012 42.286 32.012 41.422 28.603 5.999 28.603 3.489 37.539 13.237 37.539 9.831 50.317 15.476 56.996" />
                              </g>
                          </g>
                          </svg>
                          <svg xmlns="http://www.w3.org/2000/svg" class="logo-full-ribbon light" viewBox="0 0 154 215">
                          <g fill="none" fill-rule="evenodd">
                              <polygon class="ribbon" fill="#000" points="0 0 154 0 154 215 77 166.153 0 215" />
                              <g transform="translate(23 21)">
                              <path class="text" fill="#FFF"
                                  d="M0.0598153846,58.2444393 L0.0598153846,60.3116763 L0.522276923,60.0186821 C0.642738462,59.9439538 0.780507692,59.9025202 0.921738462,59.9025202 L1.57458462,59.9025202 L1.57458462,67.507052 C1.57458462,67.6550289 1.53858462,67.8022659 1.46589231,67.9310058 L1.19104615,68.4252486 L3.51166154,68.4252486 L3.23750769,67.9310058 C3.16412308,67.8022659 3.12812308,67.6550289 3.12812308,67.507052 L3.12812308,59.9025202 L3.78166154,59.9025202 C3.9222,59.9025202 4.06066154,59.9439538 4.18043077,60.0186821 L4.64289231,60.3116763 L4.64289231,58.2444393 L0.0598153846,58.2444393 Z M11.5147385,58.2444393 C11.0342769,58.2444393 10.6438154,58.6617341 10.6438154,59.1722543 L10.6438154,67.4974335 C10.6438154,68.0079538 11.0342769,68.4252486 11.5147385,68.4252486 L14.3566615,68.4252486 C14.8371231,68.4252486 15.2268923,68.0079538 15.2268923,67.4974335 L15.2268923,59.1722543 C15.2268923,58.6617341 14.8371231,58.2444393 14.3566615,58.2444393 L11.5147385,58.2444393 Z M13.3265077,66.7671676 L12.5448923,66.7671676 C12.3510462,66.7671676 12.1945846,66.5999538 12.1945846,66.3964855 L12.1945846,60.2732023 C12.1945846,60.070474 12.3510462,59.9025202 12.5448923,59.9025202 L13.3265077,59.9025202 C13.5196615,59.9025202 13.6761231,60.070474 13.6761231,60.2732023 L13.6761231,66.3964855 C13.6761231,66.5999538 13.5196615,66.7671676 13.3265077,66.7671676 L13.3265077,66.7671676 Z M26.1944308,58.2444393 L24.2601231,58.2444393 L24.5252769,58.7238844 C24.6021231,58.8622428 24.6436615,59.0198382 24.6436615,59.1796532 L24.6436615,66.3846474 C24.6436615,66.5947746 24.4823538,66.7671676 24.2850462,66.7671676 L23.5207385,66.7671676 C23.3234308,66.7671676 23.1648923,66.5947746 23.1648923,66.3846474 L23.1648923,59.1796532 C23.1648923,59.0198382 23.2029692,58.8622428 23.2805077,58.7268439 L23.5477385,58.2444393 L21.2278154,58.2444393 L21.4957385,58.7268439 C21.5718923,58.8622428 21.6106615,59.0198382 21.6106615,59.1796532 L21.6106615,67.603237 C21.6106615,68.0560462 21.9554308,68.4252486 22.3798154,68.4252486 L25.4259692,68.4252486 C25.8503538,68.4252486 26.1944308,68.0560462 26.1944308,67.603237 L26.1944308,59.1796532 C26.1944308,59.0198382 26.2332,58.8622428 26.3100462,58.7268439 L26.5779692,58.2444393 L26.1944308,58.2444393 Z M37.6611231,67.9287861 C37.5905077,67.8022659 37.5545077,67.6572486 37.5545077,67.5092717 L37.5545077,63.5383121 C37.5545077,63.084763 36.7625077,62.7170405 36.3408923,62.7170405 C36.7625077,62.7170405 37.5545077,62.3507977 37.5545077,61.8972486 L37.5545077,59.0686705 C37.5545077,58.6136416 37.2104308,58.2444393 36.7832769,58.2444393 L32.5878923,58.2444393 L32.8641231,58.7409017 C32.9340462,58.8696416 32.9707385,59.0124393 32.9707385,59.1604162 L32.9707385,67.5092717 C32.9707385,67.6572486 32.9340462,67.8022659 32.8641231,67.9287861 L32.5878923,68.4252486 L34.9078154,68.4252486 L34.6308923,67.9287861 C34.5602769,67.8022659 34.5222,67.6572486 34.5222,67.5092717 L34.5222,63.5479306 L35.7067385,63.5479306 C35.8694308,63.5479306 36.0037385,63.6885087 36.0037385,63.8638613 L36.0037385,67.5092717 C36.0037385,67.6572486 35.9642769,67.8000462 35.8943538,67.9287861 L35.6202,68.4252486 L37.9380462,68.4252486 L37.6611231,67.9287861 Z M34.5242769,61.8876301 L34.5242769,59.9025202 L35.7067385,59.9025202 C35.8694308,59.9025202 36.0037385,60.0453179 36.0037385,60.2206705 L36.0037385,61.5724393 C36.0037385,61.747052 35.8694308,61.8876301 35.7067385,61.8876301 L34.5242769,61.8876301 Z M48.9145846,59.1530173 C48.9145846,59.0102197 48.9505846,58.8696416 49.0191231,58.7460809 L49.2981231,58.2444393 L46.9795846,58.2444393 L47.2585846,58.7460809 C47.3264308,58.8696416 47.3631231,59.0102197 47.3631231,59.1530173 L47.3631231,63.225341 L46.1294308,59.0102197 C46.0678154,58.7926936 46.0927385,58.5596301 46.1993538,58.3635607 L46.2678923,58.2444393 L43.9472769,58.2444393 L44.2262769,58.7460809 C44.2941231,58.8696416 44.3301231,59.0102197 44.3301231,59.1559769 L44.3301231,67.5166705 C44.3301231,67.6594682 44.2941231,67.8000462 44.2262769,67.9236069 L43.9472769,68.4252486 L46.2678923,68.4252486 L45.9888923,67.9236069 C45.9203538,67.8000462 45.8843538,67.6594682 45.8843538,67.5166705 L45.8843538,63.475422 L47.1090462,67.6713064 C47.1720462,67.8866127 47.1478154,68.1226358 47.0384308,68.3157457 L46.9795846,68.4252486 L49.2981231,68.4252486 L49.0191231,67.9236069 C48.9505846,67.8000462 48.9145846,67.6594682 48.9145846,67.5166705 L48.9145846,59.1530173 Z M60.2746615,59.1655954 C60.2746615,59.0153988 60.3127385,58.867422 60.3833538,58.7364624 L60.6575077,58.2444393 L55.3073538,58.2444393 L55.5822,58.7364624 C55.6542,58.867422 55.6902,59.0153988 55.6902,59.1655954 L55.6902,67.5040925 C55.6902,67.6550289 55.6542,67.8022659 55.5822,67.9332254 L55.3073538,68.4252486 L57.6279692,68.4252486 L57.3531231,67.9332254 C57.2804308,67.8022659 57.2444308,67.6550289 57.2444308,67.5040925 L57.2444308,64.1316994 L58.7232,63.2186821 L58.7232,67.5040925 C58.7232,67.6550289 58.6844308,67.8022659 58.6117385,67.9332254 L58.3396615,68.4252486 L60.6575077,68.4252486 L60.3833538,67.9332254 C60.3127385,67.8022659 60.2746615,67.6550289 60.2746615,67.5040925 L60.2746615,59.1655954 Z M57.2444308,62.2168786 L57.2444308,59.9025202 L58.7232,59.9025202 L58.7232,61.3038613 L57.2444308,62.2168786 Z M74.1838154,58.9354913 L74.5673538,58.2444393 L72.2495077,58.2444393 L72.5008154,58.6979884 L70.6184308,65.1379422 L68.7360462,58.6979884 L68.9880462,58.2444393 L66.6674308,58.2444393 L67.0502769,58.9354913 L67.0502769,67.7341965 L66.6674308,68.4252486 L68.9880462,68.4252486 L68.6045077,67.7341965 L68.6045077,63.5523699 L70.0285846,68.4252486 L71.2075846,68.4252486 L72.6316615,63.5523699 L72.6316615,67.7341965 L72.2495077,68.4252486 L74.5673538,68.4252486 L74.1838154,67.7341965 L74.1838154,58.9354913 Z M80.5717385,58.2444393 L80.8528154,58.7483006 C80.9206615,58.8696416 80.9545846,59.0102197 80.9545846,59.1507977 L80.9545846,67.5188902 C80.9545846,67.6594682 80.9206615,67.7978266 80.8528154,67.9213873 L80.5717385,68.4252486 L85.5383538,68.4252486 L85.5383538,66.3580116 L85.0668923,66.656185 C84.9512769,66.7279538 84.8218154,66.7671676 84.6882,66.7671676 L82.5088154,66.7671676 L82.5088154,63.5479306 L83.9121231,63.5479306 C84.0457385,63.5479306 84.1752,63.5849249 84.2915077,63.6574335 L84.7622769,63.9578266 L84.7622769,61.4777341 L84.2915077,61.7781272 C84.1752,61.8513757 84.0457385,61.8876301 83.9121231,61.8876301 L82.5088154,61.8876301 L82.5088154,59.9025202 L84.6882,59.9025202 C84.8218154,59.9025202 84.9512769,59.9417341 85.0668923,60.0142428 L85.5383538,60.3116763 L85.5383538,58.2444393 L80.5717385,58.2444393 Z M96.5038154,59.1530173 C96.5038154,59.0102197 96.5398154,58.8696416 96.6083538,58.7460809 L96.8873538,58.2444393 L94.5688154,58.2444393 L94.8478154,58.7460809 C94.9156615,58.8696416 94.9523538,59.0102197 94.9523538,59.1530173 L94.9523538,63.225341 L93.7186615,59.0102197 C93.6570462,58.7926936 93.6819692,58.5596301 93.7885846,58.3635607 L93.8571231,58.2444393 L91.5365077,58.2444393 L91.8155077,58.7460809 C91.8833538,58.8696416 91.9193538,59.0102197 91.9193538,59.1559769 L91.9193538,67.5166705 C91.9193538,67.6594682 91.8833538,67.8000462 91.8155077,67.9236069 L91.5365077,68.4252486 L93.8571231,68.4252486 L93.5781231,67.9236069 C93.5095846,67.8000462 93.4735846,67.6594682 93.4735846,67.5166705 L93.4735846,63.475422 L94.6982769,67.6713064 C94.7612769,67.8866127 94.7370462,68.1226358 94.6276615,68.3157457 L94.5688154,68.4252486 L96.8873538,68.4252486 L96.6083538,67.9236069 C96.5398154,67.8000462 96.5038154,67.6594682 96.5038154,67.5166705 L96.5038154,59.1530173 Z M102.896585,58.2444393 L102.896585,60.3116763 L103.359046,60.0186821 C103.479508,59.9439538 103.617969,59.9025202 103.758508,59.9025202 L104.412046,59.9025202 L104.412046,67.507052 C104.412046,67.6550289 104.375354,67.8022659 104.302662,67.9310058 L104.028508,68.4252486 L106.348431,68.4252486 L106.074277,67.9310058 C106.001585,67.8022659 105.964892,67.6550289 105.964892,67.507052 L105.964892,59.9025202 L106.618431,59.9025202 C106.758969,59.9025202 106.897431,59.9439538 107.017892,60.0186821 L107.480354,60.3116763 L107.480354,58.2444393 L102.896585,58.2444393 Z" />
                              <path class="crown" fill="#F7BE00"
                                  d="M22.2759692 93.4093688C22.2759692 91.3828254 20.7342 89.734363 18.8462769 89.734363L15.1839692 89.734363 23.9901231 73.4391491 15.9759692 73.4391491 8.72266154 86.8717503 8.72266154 73.4391491.0591230769 73.4391491 1.77327692 76.5289064 1.77327692 115.884837.0591230769 118.974594 8.72266154 118.974594 8.72266154 97.1613225 13.8048923 97.1613225C14.6467385 97.1613225 15.3362769 97.8871491 15.3362769 98.7979468L15.3362769 118.974594 23.9901231 118.974594 22.2759692 115.884837 22.2759692 93.4093688zM34.7125846 77.6350335C34.7125846 76.9077272 34.8856615 76.2040971 35.2304308 75.5855538L36.4274308 73.4391491 26.0497385 73.4391491 27.2467385 75.5855538C27.5915077 76.2040971 27.7638923 76.9077272 27.7638923 77.6350335L27.7638923 114.789808C27.7638923 115.505276 27.5915077 116.210386 27.2467385 116.82819L26.0497385 118.974594 36.4274308 118.974594 35.2304308 116.82819C34.8856615 116.210386 34.7022 115.505276 34.7022 114.789808L34.7022 77.6350335 34.7125846 77.6350335zM60.6831231 77.5048139C60.6831231 76.8648139 60.8451231 76.236652 61.1497385 75.6832185L62.3972769 73.4391491 52.0292769 73.4391491 53.2775077 75.6832185C53.5821231 76.236652 53.7441231 76.8648139 53.7441231 77.5048139L53.7441231 95.7192879 48.2257385 76.8648139C47.9515846 75.8896462 48.0630462 74.8486289 48.5393538 73.9703861L48.8439692 73.4391491 38.4662769 73.4391491 39.7145077 75.6832185C40.0177385 76.236652 40.1804308 76.8648139 40.1804308 77.5159121L40.1804308 114.909669C40.1804308 115.548929 40.0177385 116.177831 39.7145077 116.730525L38.4662769 118.974594 48.8439692 118.974594 47.5964308 116.730525C47.2918154 116.177831 47.1298154 115.548929 47.1298154 114.909669L47.1298154 96.8357734 52.6080462 115.603681C52.8912 116.56775 52.7804308 117.619866 52.2930462 118.48701L52.0292769 118.974594 62.3972769 118.974594 61.1497385 116.730525C60.8451231 116.177831 60.6831231 115.548929 60.6831231 114.909669L60.6831231 77.5048139zM83.1533538 89.8320277C84.2693538 89.1491145 84.9588923 87.8698543 84.9588923 86.4921896L84.9588923 77.0490451C84.9588923 75.0543168 83.4475846 73.4391491 81.5804308 73.4391491L67.8346615 73.4391491C65.9682 73.4391491 64.4568923 75.0543168 64.4568923 77.0490451L64.4568923 115.364698C64.4568923 117.359427 65.9682 118.974594 67.8346615 118.974594L78.0198923 118.974594 78.0198923 123.398363 84.9588923 127.680814 84.9588923 93.8325827 73.8605077 100.684652 78.0198923 103.243172 78.0198923 107.601831 78.0198923 111.559473 71.4055846 111.559473 71.4055846 80.8550104 78.0198923 80.8550104 78.0198923 93.0090913 83.1533538 89.8320277zM101.586046 73.4391491L90.4475077 73.4391491C88.5298154 73.4391491 86.9783538 75.0972301 86.9783538 77.1467098L86.9783538 93.453022C86.9783538 95.4914035 88.5298154 97.1502243 90.4475077 97.1502243L98.8978154 97.1502243C99.8109692 97.1502243 100.541354 97.9308023 100.541354 98.9067098L100.541354 109.922109C100.541354 110.821808 99.8518154 111.559473 98.9995846 111.559473L95.6114308 111.559473C94.6782 111.559473 93.9166615 110.756698 93.9166615 109.759334L93.9166615 100.001739 88.7125846 103.221716C87.6478154 103.883172 86.9783538 105.097323 86.9783538 106.430594L86.9783538 115.267034C86.9783538 117.315773 88.5298154 118.974594 90.4475077 118.974594L104.0112 118.974594C105.9282 118.974594 107.480354 117.315773 107.480354 115.267034L107.480354 93.4419237C107.480354 91.3931838 105.9282 89.734363 104.0112 89.734363L95.4189692 89.734363C94.5972 89.734363 93.9166615 89.0181549 93.9166615 88.1399121L93.9166615 82.6011376C93.9166615 81.6355884 94.6574308 80.8550104 95.5602 80.8550104L103.564662 80.8550104C104.234123 80.8550104 104.8932 81.0503399 105.462277 81.4188023L107.480354 82.6869642 107.480354 77.1467098C107.480354 75.0972301 105.9282 73.4391491 104.0112 73.4391491L101.586046 73.4391491zM83.8318846 48.0347006L93.7658077 16.5881341 73.4050385 27.7448509C73.9401923 28.780689 74.2448077 29.971163 74.2448077 31.238585 74.2448077 35.2369202 71.2118077 38.4783538 67.4698846 38.4783538 63.7286538 38.4783538 60.6949615 35.2369202 60.6949615 31.238585 60.6949615 28.7740301 61.8476538 26.5980301 63.6075 25.2899145L53.9255769.703556069 44.2436538 25.2899145C46.0028077 26.5980301 47.1555 28.7740301 47.1555 31.238585 47.1555 35.2369202 44.1225 38.4783538 40.3805769 38.4783538 36.6393462 38.4783538 33.6063462 35.2369202 33.6063462 31.238585 33.6063462 29.971163 33.9102692 28.780689 34.4454231 27.7448509L14.0853462 16.5881341 24.0185769 48.0347006.0591923077 48.0347006.0591923077 53.2146312 107.346115 53.2146312 107.346115 48.0347006 83.8318846 48.0347006z" />
                              </g>
                          </g>
                          </svg>
                          <span class="sr-only">Tournament Kings</span>
                      </a>
                      <div class="navbar-controls logged-out">
                          <nav class="navbar-desktop">
                            <ul class="nav">
                              <li><a href="/register">Register</a></li>
                              <li><a href="/login">Login</a></li>
                            </ul>
                          </nav>
                          <button type="button" class="navbar-toggle navbar-toggle-login">
                            <a href="/login">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon-user" viewBox="0 0 29 30">
                                <path class="user-fill"
                                d="M15,25.1503125 C18.4941797,25.1503125 21.5917969,23.3046094 23.4375,20.6014453 C23.3710547,17.8328906 17.7685547,16.2508594 15,16.2508594 C12.165,16.2508594 6.62789062,17.8328906 6.5625,20.6014453 C8.40820312,23.3046094 11.506875,25.1503125 15,25.1503125 Z M15,5.17769531 C12.6923437,5.17769531 10.78125,7.08984375 10.78125,9.39644531 C10.78125,11.7030469 12.6933984,13.6151953 15,13.6151953 C17.3066016,13.6151953 19.21875,11.7030469 19.21875,9.39644531 C19.21875,7.08984375 17.3066016,5.17769531 15,5.17769531 Z M15,0.958945312 C22.7783203,0.958945312 29.0410547,7.22167969 29.0410547,15 C29.0410547,22.7783203 22.7783203,29.0410547 15,29.0410547 C7.22167969,29.0410547 0.958945312,22.7783203 0.958945312,15 C0.958945312,7.22167969 7.22167969,0.958945312 15,0.958945312 Z"
                                transform="translate(-.5)" />
                              </svg>
                            </a>
                            {{-- <span class="tooltip bottom flush-r">Login</span> --}}
                          </button>

                          <button type="button" class="navbar-toggle navbar-toggle-menu collapsed" data-toggle="collapse"
                          data-target="#main-menu" aria-expanded="false">
                          <span class="sr-only">Toggle navigation</span>
                          <span class="icon-bar top"></span>
                          <span class="icon-bar mid"></span>
                          <span class="icon-bar bottom"></span>
                          </button>
                      </div>
                  </div>
              </div>
            </nav>

            <section class="hero">
                <div class="text">
                    <div class="text-wrap">
                      <h1 class="title">Put Your Money Where Your Mouth is</h1>
                      <p class="subtitle">{{__('landing.tk-is')}}</p>
                      <div class="actions">
                        <a href="/early-access/register">{{__('landing.early-reg-btn')}}</a>
                      </div>
                    </div>
                </div>

                <div class="media">
                    <img class="hero-img img-overlay" src="media/images/content/img-tk-gamer-03.jpg" alt="">
                </div>

                <div data-name="Perspective View" class="glider-contain multiple" hidden>
                    <div class="glider glider-persp" id="glider-persp">
                      <div>
                        <div class="gradient-border-bottom">
                          <div class="gradient-border">
                            <img src="/media/images/content/feature-card.png" alt="">
                          </div>
                        </div>
                      </div>
                      <div>
                        <div class="gradient-border-bottom">
                          <div class="gradient-border">
                            <img src="/media/images/content/feature-card.png" alt="">
                          </div>
                        </div>
                      </div>
                      <div>
                        <div class="gradient-border-bottom">
                          <div class="gradient-border">
                            <img src="/media/images/content/feature-card.png" alt="">
                          </div>
                        </div>
                      </div>
                      <div>
                        <div class="gradient-border-bottom">
                          <div class="gradient-border">
                            <img src="/media/images/content/feature-card.png" alt="">
                          </div>
                        </div>
                      </div>
                      <div>
                        <div class="gradient-border-bottom">
                          <div class="gradient-border">
                            <img src="/media/images/content/feature-card.png" alt="">
                          </div>
                        </div>
                      </div>
                      <div>
                        <div class="gradient-border-bottom">
                          <div class="gradient-border">
                            <img src="/media/images/content/feature-card.png" alt="">
                          </div>
                        </div>
                      </div>
                      <div>
                        <div class="gradient-border-bottom">
                          <div class="gradient-border">
                            <img src="/media/images/content/feature-card.png" alt="">
                          </div>
                        </div>
                      </div>
                      <div>
                        <div class="gradient-border-bottom">
                          <div class="gradient-border">
                            <img src="/media/images/content/feature-card.png" alt="">
                          </div>
                        </div>
                      </div>
                      <div>
                        <div class="gradient-border-bottom">
                          <div class="gradient-border">
                            <img src="/media/images/content/feature-card.png" alt="">
                          </div>
                        </div>
                      </div>
                      <div>
                        <div class="gradient-border-bottom">
                          <div class="gradient-border">
                            <<img src="/media/images/content/feature-card.png" alt="">
                          </div>
                        </div>
                      </div>
                    </div>
                    <button role="button" aria-label="Previous" class="glider-prev" id="glider-prev-per">
                      <i class="fa fa-chevron-left">&lt;</i>
                    </button>
                    <button role="button" aria-label="Next" class="glider-next" id="glider-next-per"><i class="fa fa-chevron-right">></i></button>
                    <div id="dots-per"></div>
                  </div>

                <!-- Latest Tournaments -->
                <div id="carousel-latest-tournaments" class="carousel card--carousel" data-ride="carousel">

                  <!-- Wrapper for slides -->
                  <div class="carousel-inner" role="listbox">
                    <div class="item active">

                      <article class="card">
                        <button class="toggle--close" type="button">Close</button>
                          <figure class="card--media">
                            <a href="#0"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/97621/img-16x9-FIFA19.jpg" alt=""></a>
                          </figure>
                          <div class="card--content">
                            <h2 class="card--title">FIFA</h2>
                            <p class="card--label">Tournament Name</p>
                            <p class="card--copy">Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                            <div class="card--actions">
                              <a href="#0" class="btn-primary-dark">Sign Up</a>
                            </div>
                          </div>
                        </article>

                      </div>
                    <div class="item">
                      <article class="card">
                        <button class="toggle--close" type="button">Close</button>
                        <figure class="card--media">
                          <a href="#0"><img src="//s3-us-west-2.amazonaws.com/s.cdpn.io/97621/img-16x9-COD.jpg" alt=""></a>
                        </figure>
                        <div class="card--content">
                          <h2 class="card--title">Call of Duty</h2>
                          <p class="card--label">Tournament Name</p>
                          <p class="card--copy">Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                          <div class="card--actions">
                            <a href="#0" class="btn-primary-dark">Sign Up</a>
                          </div>
                        </div>
                      </article>
                    </div>

                    <div class="item">
                      <article class="card">
                        <button class="toggle--close" type="button">Close</button>
                        <figure class="card--media">
                          <a href="#0"><img src="//s3-us-west-2.amazonaws.com/s.cdpn.io/97621/img-16x9-Fortnite.jpg" alt=""></a>
                        </figure>
                        <div class="card--content">
                          <h2 class="card--title">Fortnite</h2>
                          <p class="card--label">Tournament Name</p>
                          <p class="card--copy">Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                          <div class="card--actions">
                            <a href="#0" class="btn-primary-dark">Sign Up</a>
                          </div>
                        </div>
                      </article>
                    </div>

                    <div class="item">
                      <article class="card">
                        <button class="toggle--close" type="button">Close</button>
                        <figure class="card--media">
                          <a href="#0"><img src="//s3-us-west-2.amazonaws.com/s.cdpn.io/97621/img-16x9-DOTA.jpg" alt=""></a>
                        </figure>
                        <div class="card--content">
                          <h2 class="card--title">Dota 2 Auto Chess</h2>
                          <p class="card--label">Tournament Name</p>
                          <p class="card--copy">Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                          <div class="card--actions">
                            <a href="#0" class="btn-primary-dark">Sign Up</a>
                          </div>
                        </div>
                      </article>
                    </div>

                    <div class="item">
                      <article class="card">
                        <button class="toggle--close" type="button">Close</button>
                        <figure class="card--media">
                          <a href="#0"><img src="//s3-us-west-2.amazonaws.com/s.cdpn.io/97621/img-16x9-League-of-Legends.jpg" alt=""></a>
                        </figure>
                        <div class="card--content">
                          <h2 class="card--title">League of Legends</h2>
                          <p class="card--label">Tournament Name</p>
                          <p class="card--copy">Lorem ipsum, dolor sit amet consectetur adipisicing elit.</p>
                          <div class="card--actions">
                            <a href="#0" class="btn-primary-dark">Sign Up</a>
                          </div>
                        </div>
                      </article>
                    </div>
                  </div>

                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-latest-tournaments" role="button" data-slide="prev">
                      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-latest-tournaments" role="button" data-slide="next">
                      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>



                <div class="footer-desktop">
                  <p class="copyrights">© 2019 Tournament Kings &nbsp; • &nbsp; <a href="/terms">Terms of Service</a> &nbsp; • &nbsp; <a href="mailto:info@tournamentkings.com?Subject=Hello%20again">Contact Us</a>
                  <ul class="icon-list">
                      {{-- <li>
                        <a href="mailto:info@tournamentkings.com?Subject=Hello%20again">
                          <svg class="icon">
                            <use xlink:href="#icon-email"></use>
                          </svg>
                          <span class="sr-only">Contact Us</span>
                        </a>
                      </li> --}}
                      <li class="has-tooltip">
                          <a href="//twitter.com/TKpays">
                            <svg class="icon">
                              <use xlink:href="#icon-twitter"></use>
                            </svg>
                            <span class="sr-only">Follow us on Twitter</span>
                            <span class="tooltip">Twitter</span>
                          </a>
                      </li>
                      <li class="has-tooltip">
                        <a href="//www.facebook.com/tkpays/">
                          <svg class="icon icon-fb">
                            <use xlink:href="#icon-facebook"></use>
                          </svg>
                          <span class="sr-only">Find us on Facebook</span>
                          <span class="tooltip flush-r">Facebook</span>
                        </a>
                      </li>
                  </ul>
                </div>
            </section>
        </header>

        <main id="main-content" role="main">

            <section class="callout">
            <div class="container">
                <h2 id="tournament-kings">
                    <img class="logo" src="media/images/ui/logo-tk-crown-yellow.svg" alt="">
                    <span class="sr-only">Tournament Kings</span>
                </h2>
                <p>{{__('landing.tk-is')}}</p>
                <a href="/register" class="btn-primary-dark">Free Registration</a>
            </div>
            </section>

            <section class="info">
              <header>
                  <h2 id="how-it-works">How it Works</h2>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 97 32">
                    <defs>
                      <polygon id="icon-arrow-down-a" points="80 155 176 155 176 163.421 128 187 80 163.421"/>
                    </defs>
                    <g fill="none" fill-rule="evenodd" transform="translate(-79.5 -155)">
                      <use fill="#636569" xlink:href="#icon-arrow-down-a"/>
                    </g>
                  </svg>
              </header>

              <div class="steps">
                  <article class="step">
                    <h2 class="counter-item counter-item-one">{{__('landing.join-paid-or-free')}}</h2>
                  </article>
                  <article class="step">
                    <h2 class="counter-item counter-item-two">{{__('landing.compete-online')}}</h2>
                  </article>
                  <article class="step">
                    <h2 class="counter-item counter-item-three">{{__('landing.win-cash')}}</h2>
                  </article>
                  <div class="actions">
                    <a href="/register" class="btn-primary-dark">Free Registration</a>
                  </div>
              </div>
            </section>

            <section class="join-beta theme-light">
            <div class="container">
                <h2 id="join-beta-group">Want to Join our Beta Group?</h2>
                <p>Build this dream with us. All beta group members will get lifetime benefits and perks that are actually worthwhile. Any and all help you can provide will be forever remembered and appreciated.</p>
                <a href="/early-access/register" class="btn-primary-dark">Sign Up</a>
            </div>
            </section>

        </main>

        <footer class="site-footer" role="contentinfo">
            <div class="container">
            <img class="logo-icon logo-icon-sm pb-2" src="media/images/ui/logo-tk-logo.svg" alt="Tournament Kings">
            <p><strong>Got something (meaningful) to say? Drop us a line.</strong></p>
            <a href="mailto:info@tournamentkings.com?Subject=Hello%20again" class="btn-outline-light">Contact Us</a>
            <ul class="icon-list">
                <li>
                <a href="//www.facebook.com/tkpays/">
                    <svg class="icon">
                    <use xlink:href="#icon-facebook"></use>
                    </svg>
                    <span class="sr-only">Find us on Facebook</span>
                </a>
                </li>
                <li>
                <a href="//twitter.com/TKpays">
                    <svg class="icon">
                    <use xlink:href="#icon-twitter"></use>
                    </svg>
                    <span class="sr-only">Follow us on Twitter</span>
                </a>
                </li>
                <!--<li>
                <a href="#0">
                    <svg class="icon">
                    <use xlink:href="#icon-twitch"></use>
                    </svg>
                    <span class="sr-only">Watch us on Twitch</span>
                </a>
                </li>-->
            </ul>
            <p class="copyrights">Copyright © 2019 Tournamentkings.com All right reserved. <a href="/terms">Terms of Service</a>.
            </p>
            </div>
        </footer>

        <!-- JS dependencies -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

        <!-- Main Menu toggle -->
        <script>
            $(document).ready(function () {
              $('#main-menu').on('show.bs.collapse', function () {
                  $('html').addClass('main-menu-visible');
              })

              $('#main-menu').on('hide.bs.collapse', function () {
                  $('html').removeClass('main-menu-visible');
              })
            })

            $('.carousel').carousel({
              interval: 6000
            })
        </script>

        {{-- <script>
          new Glider(document.querySelector('.glider'), {
            slidesToShow: 3,
            slidesToScroll: 1,
            draggable: true,
            // itemWidth: 300,
            dots: '.dots',
            arrows: {
              prev: '.glider-prev',
              next: '.glider-next'
            }
          })
        </script> --}}
    </body>
</html>
