<div class="app-wrapper-footer">
    <div class="app-footer">
        <div class="app-footer__inner">
            <div class="app-footer-left">
                <ul class="nav">
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link">
                            <b class="text-primary">PPS| Top Dental</b>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link">
                            v1.1.2
                        </a>
                    </li>
                </ul>
            </div>
            <div class="app-footer-right">
                <ul class="nav">
                    <li class="nav-item">
                        <a href="https://t.me/paohno" class="nav-link" target="_blank">
                            <b>Contact Support  <b>
                            <!-- <span class="spinner-grow spinner-grow-sm mr-2 text-danger"></span> -->
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link">
                            <!-- <div class="badge badge-success mr-1 ml-0">
                                <small>NEW</small>
                            </div> -->
                            <b class="text-alternate" id="updatesRecap">Updates</b>
                            <span class="spinner-grow spinner-grow-sm mr-2 text-danger"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $('#updatesRecap').click(function (){
        swal.fire({
            title: '<strong>PPSoft </strong> v1.1.2 Release',
            icon: 'info',
            html:
                'Now, you can do <b>Walkout process</b> in Soft. <br>(' +
                '<a href="#" class="text-alternate"">Remember:</a> ' +
                ' The Software will log out automatically for a period of inactivity of one hour)',
        });
    });
</script>