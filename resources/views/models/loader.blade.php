<div class="modal fade" id="loader" tabindex="-1" role="dialog" aria-labelledby="verticalModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verticalModalTitle">@lang("برجاء الانتظار")</h5>

            </div>
            <style>


                .modal-body {
                    padding: 0;
                    margin: 0;

                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                }

                .loader {
                    position: relative;
                    display: block;
                    width: 300px;
                    height: 300px;
                    transform-style: preserve-3d;
                    transform: perspective(1000px) rotateY(45deg) rotateX(45deg);
                }

                .circle:nth-child(0) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 0px;
                    top: 0px;
                    width: 300px;
                    height: 300px;
                    -webkit-animation: spin Infinitys infinite linear;
                    animation: spin Infinitys infinite linear;
                }

                .circle:nth-child(1) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 9.375px;
                    top: 9.375px;
                    width: 281.25px;
                    height: 281.25px;
                    -webkit-animation: spin 12s infinite linear;
                    animation: spin 12s infinite linear;
                }

                .circle:nth-child(2) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 18.75px;
                    top: 18.75px;
                    width: 262.5px;
                    height: 262.5px;
                    -webkit-animation: spin 6s infinite linear;
                    animation: spin 6s infinite linear;
                }

                .circle:nth-child(3) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 28.125px;
                    top: 28.125px;
                    width: 243.75px;
                    height: 243.75px;
                    -webkit-animation: spin 4s infinite linear;
                    animation: spin 4s infinite linear;
                }

                .circle:nth-child(4) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 37.5px;
                    top: 37.5px;
                    width: 225px;
                    height: 225px;
                    -webkit-animation: spin 3s infinite linear;
                    animation: spin 3s infinite linear;
                }

                .circle:nth-child(5) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 46.875px;
                    top: 46.875px;
                    width: 206.25px;
                    height: 206.25px;
                    -webkit-animation: spin 2.4s infinite linear;
                    animation: spin 2.4s infinite linear;
                }

                .circle:nth-child(6) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 56.25px;
                    top: 56.25px;
                    width: 187.5px;
                    height: 187.5px;
                    -webkit-animation: spin 2s infinite linear;
                    animation: spin 2s infinite linear;
                }

                .circle:nth-child(7) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 65.625px;
                    top: 65.625px;
                    width: 168.75px;
                    height: 168.75px;
                    -webkit-animation: spin 1.714285714285714s infinite linear;
                    animation: spin 1.714285714285714s infinite linear;
                }

                .circle:nth-child(8) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 75px;
                    top: 75px;
                    width: 150px;
                    height: 150px;
                    -webkit-animation: spin 1.5s infinite linear;
                    animation: spin 1.5s infinite linear;
                }

                .circle:nth-child(9) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 84.375px;
                    top: 84.375px;
                    width: 131.25px;
                    height: 131.25px;
                    -webkit-animation: spin 1.333333333333333s infinite linear;
                    animation: spin 1.333333333333333s infinite linear;
                }

                .circle:nth-child(10) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 93.75px;
                    top: 93.75px;
                    width: 112.5px;
                    height: 112.5px;
                    -webkit-animation: spin 1.2s infinite linear;
                    animation: spin 1.2s infinite linear;
                }

                .circle:nth-child(11) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 103.125px;
                    top: 103.125px;
                    width: 93.75px;
                    height: 93.75px;
                    -webkit-animation: spin 1.090909090909091s infinite linear;
                    animation: spin 1.090909090909091s infinite linear;
                }

                .circle:nth-child(12) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 112.5px;
                    top: 112.5px;
                    width: 75px;
                    height: 75px;
                    -webkit-animation: spin 1s infinite linear;
                    animation: spin 1s infinite linear;
                }

                .circle:nth-child(13) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 121.875px;
                    top: 121.875px;
                    width: 56.25px;
                    height: 56.25px;
                    -webkit-animation: spin 0.923076923076923s infinite linear;
                    animation: spin 0.923076923076923s infinite linear;
                }

                .circle:nth-child(14) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 131.25px;
                    top: 131.25px;
                    width: 37.5px;
                    height: 37.5px;
                    -webkit-animation: spin 0.857142857142857s infinite linear;
                    animation: spin 0.857142857142857s infinite linear;
                }

                .circle:nth-child(15) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 140.625px;
                    top: 140.625px;
                    width: 18.75px;
                    height: 18.75px;
                    -webkit-animation: spin 0.8s infinite linear;
                    animation: spin 0.8s infinite linear;
                }

                .circle:nth-child(16) {
                    position: absolute;
                    background: transparent;
                    border: 2px solid #fff;
                    border-radius: 50%;
                    left: 150px;
                    top: 150px;
                    width: 0px;
                    height: 0px;
                    -webkit-animation: spin 0.75s infinite linear;
                    animation: spin 0.75s infinite linear;
                }

                .circle:nth-child(2n) {
                    border: 2px dashed rgba(255, 255, 255, 0.5);
                }

                .circle:last-child {
                    display: none;
                }

                @-webkit-keyframes spin {
                    0% {
                        transform: rotateX(0deg);
                    }
                    100% {
                        transform: rotateX(360deg);
                    }
                }

                @keyframes spin {
                    0% {
                        transform: rotateX(0deg);
                    }
                    100% {
                        transform: rotateX(360deg);
                    }
                }


            </style>
            <div class="modal-body">
                <div class="loader-container">
                    <div class="loader">
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <div class='print'></div>


        </div>
    </div>
</div>
