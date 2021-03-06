<!-- Banner Area Starts -->
<section class="banner-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="banner-bg"></div>
            </div>
            <div class="col-lg-6 align-self-center">
                <div class="banner-text">
                    <h1>Perpustakaan Digital <span>EPERPUS</span> dengan DRM dan MFCMHPRS</h1>
                    <p class="py-3">Temukan dan baca buku digital yang direkomendasi untuk mu! dengan pengalaman membaca
                        diperangkat atau browser anda.</p>
                    <a href="#semuabuku" class="secondary-btn">Telusuri sekarang<span class="flaticon-next"></span></a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Banner Area End -->


<!-- Semua Buku -->
<section id="blog" class="news-area section-padding3">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-top">
                    <br>
                    <br>
                    <h2>Semua Buku dalam Klasifikasi</h2>
                    <p>Temukan semua buku digital dalam klasifikasi</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="kartu-columns col-lg-12">
                <?php

                    foreach ($results as $row) {
                        $kalimat = "$row->b_deskripsi";
                        $jumlahkarakter = 90;
                        $cetak = substr($kalimat, 0, $jumlahkarakter);
                        ?>


                <div class="card">
                    <img src="<?php echo base_url('assets/img/buku/' . $row->b_sampul); ?>" class="card-img-top"
                        alt="cover">
                    <div class="card-body">
                        <p class="card-title"><strong><?php echo $row->b_judul; ?></strong></p>
                        <p class="card-text"><small><?php echo $cetak; ?>...</small></p>
                    </div>
                    <ul class="list-group list-group-flush text-center">
                        <?php
                    $nilai = "$row->b_rating";

                        echo "<span> <small>Rating :</small> ";
                        for ($i = 0; $i < 5; $i++) {
                            if ($i < $nilai) {
                                echo "<i class='fa fa-star' aria-hidden='true'></i>
                                                            ";
                            } else {
                                echo "<i class='fa fa-star-o' aria-hidden='true'></i>";
                            }

                        }
                        echo "</span>";?>

                    </ul>
                    <div class="card-footer">
                        <a href="<?php echo site_url('controller_landing/detailBuku/' . $row->b_idbuku) ?>"
                            class="btn btn-primary btn-lg btn-block btn-sm">Rincian Buku
                        </a>
                    </div>
                </div>

                <?php }
            ?>
            </div>
        </div>


        <div class="col-lg-12">
            <p><?php echo $links; ?></p>
        </div>
    </div>
    </div>
</section>