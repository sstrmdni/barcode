<?php
if (isset($_GET['resi'])) {
    $resi = $_GET['resi'];

    // Lakukan query untuk mendapatkan informasi berdasarkan resi
    $query = "SELECT *
    FROM shop_id
    INNER JOIN toko_id ON shop_id.id_product = toko_id.id_product
    INNER JOIN product_toko_id ON toko_id.id_product = product_toko_id.id_product
    WHERE resi = '$resi';
    ";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Periksa apakah ada baris data
        if (mysqli_num_rows($result) > 0) {
            // Loop melalui setiap baris data
            while ($productData = mysqli_fetch_assoc($result)) {
                // Tampilkan informasi produk   
                echo "ID Shop: " . htmlspecialchars($productData['id_shop']) . "<br>";
                echo "Invoice: " . htmlspecialchars($productData['invoice']) . "<br>";
                echo "Image: " . htmlspecialchars($productData['image']) . "<br>";
                echo "Tanggal Bayar: " . htmlspecialchars($productData['tanggal_bayar']) . "<br>";
                echo "Nama Product: " . htmlspecialchars($productData['nama_product']) . "<br>";
                echo "SKU Toko: " . htmlspecialchars($productData['sku_toko']) . "<br>";
                echo "Jumlah: " . htmlspecialchars($productData['jumlah']) . "<br>";
                echo "Penerima: " . htmlspecialchars($productData['penerima']) . "<br>";
                echo "Kurir: " . htmlspecialchars($productData['kurir']) . "<br>";
                echo "Tipe: " . htmlspecialchars($productData['tipe']) . "<br>";
                echo "Resi: " . htmlspecialchars($productData['resi']) . "<br>";
                echo "Tanggal Pengiriman: " . htmlspecialchars($productData['tanggal_pengiriman']) . "<br>";
                echo "Waktu Pengiriman: " . htmlspecialchars($productData['waktu_pengiriman']) . "<br>";
                echo "Olshop: " . htmlspecialchars($productData['olshop']) . "<br>";
            }
        } else {
            echo "No products found.";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);
} else {
    echo "Invalid resi.";
}
?>


<div id="mobileCardContainer" class="d-none">

    <div class="col-xl-6">

        <div class="row">

            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">

                <div class="card">

                    <div class="card-body p-3">

                        <div class="row justify-content-center">

                            <div class="col-auto">

                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">

                                    <a data-bs-toggle="modal" data-bs-target="#exampleModal1"><i class="fas fa-clipboard-check text-lg opacity-10" aria-hidden="true"></i></a>

                                </div>

                            </div>

                            <div class="col-auto">

                                <div class="icon icon-shape bg-gradient-secondary shadow-primary text-center rounded-circle">

                                    <a><i onclick="redirectTorefillPage()" class="fas fa-download text-lg opacity-10" aria-hidden="true"></i></a>

                                </div>

                            </div>

                            <script>

                                function redirectTorefillPage() {

                                    // Ganti URL halaman tujuan sesuai dengan kebutuhan Anda

                                    window.location.href = "exportrefill.php";

                                }

                            </script>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card h-100">

                <div class="card-header pb-0 p-3">

                    <div class="row">

                        <div class="col-6 d-flex align-items-center">

                            <h6 class="mb-0">History</h6>

                        </div>

                    </div>

                </div>

                <div class="card-body p-3 pb-0">

                    <ul class="list-group">

                        <?php

                        $per_page = 10;



                        $query_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM request_id, toko_id, product_toko_id WHERE request_id.id_toko=toko_id.id_toko AND toko_id.id_product=product_toko_id.id_product $sku_condition");

                        $row_total = mysqli_fetch_assoc($query_total);

                        $total_data = $row_total['total'];

                        $total_pages = ceil($total_data / $per_page);



                        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                        $start = ($page_number - 1) * $per_page;



                        $select = mysqli_query($conn, "SELECT request_id.id_request, invoice, nama, image, image_toko, sku_toko, requester, date, picker, quantity_req, type_req, status_req, tipe_pesanan FROM request_id, toko_id, product_toko_id WHERE request_id.id_toko=toko_id.id_toko AND toko_id.id_product=product_toko_id.id_product $sku_condition ORDER BY 

                CASE WHEN status_req = 'unprocessed' THEN 0

                    WHEN status_req = 'On Process' THEN 1

                    ELSE 2

                END,

                date DESC LIMIT $start, $per_page");

                        $i = ($page_number - 1) * $per_page + 1;



                        while ($list = mysqli_fetch_array($select)) {

                            $stat = $list['status_req'];

                            $gambar = $list['image'];



                            $img = ''; // Default value for the image

                            if ($gambar == null) {
                                    // jika tidak ada gambar
                                    $img = '<img src="../../assets/img/noimageavailable.png" class="zoomable avatar avatar-sm rounded-circle me-2">';
                                } else {
                                    //jika ada gambar
                                    $img = '<img src="../../assets/img/' . $gambar . '" class="zoomable avatar avatar-sm rounded-circle me-2">';
                                }


                            $namaFull = $list['nama'];

                            $namaShort = $namaFull; // Default value for short name



                            if (strlen($namaFull) > 40) {

                                $namaShort = substr($namaFull, 0, 40) . '...';

                            }



                            $row_class = '';

                            if ($stat == 'unprocessed') {

                                $row_class = 'style="background-color: #ff000020;"';

                            } elseif ($stat == 'On Process') {

                                $row_class = 'style="background-color: #ffae0020;"';

                            } else {

                                $row_class = 'style="background-color: #00000009;"';

                            }

                        ?>

                            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg" <?= $row_class; ?>>

                                <div class="d-flex flex-column">

                                    <?php

                                    if ($stat == 'unprocessed') {

                                        $button = "redirectTohapusPage(" . $list['id_request'] . ")";

                                    } elseif ($stat == "On Process") {

                                        $button = "redirectToeditPage(" . $list['id_request'] . ")";

                                    } else {

                                        $button = "";

                                    }

                                    ?>

                                    <a onclick="<?= $button; ?>">

                                        <h6 class="m-1 text-dark font-weight-bold text-sm"><?= $list['nama']; ?></h6>

                                    </a>

                                    <span class="text-xs m-1"><?= $list['sku_toko']; ?>, &nbsp;&nbsp;&nbsp;&nbsp; [<?= $list['type_req']; ?>] </span>
				Quantity : <?= $list['quantity_req']; ?>
                                    <span class="text-xs m-1"><?= $list['date']; ?> </span>

                                </div>

                                <script>

                                    function redirectTohapusPage(id_request) {

                                        // Ganti URL halaman tujuan sesuai dengan kebutuhan Anda

                                        window.location.href = "index.php?url=hapus&id_request=" + id_request;

                                    }

                                </script>

                                <script>

                                    function redirectToeditPage(id_request) {

                                        // Ganti URL halaman tujuan sesuai dengan kebutuhan Anda

                                        window.location.href = "index.php?url=Ubah&id_request=" + id_request;

                                    }

                                </script>

                                <div class="d-flex align-items-center text-sm">

                                    <?= $img; ?>

                                </div>

                            </li>

                        <?php

                        }

                        ?>

                    </ul>



                </div>

            </div>



        </div>

    </div>

</div>
