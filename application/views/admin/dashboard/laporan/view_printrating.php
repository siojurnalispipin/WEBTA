<html>
<head>
  <title>Cetak PDF</title>
  <style>
    table {
      border-collapse:collapse;
      table-layout:fixed;width: 630px;
    }
    table td {
      word-wrap:break-word;
      width: 20%;
    }
  </style>
</head>
<body>
    <b><?php echo $ket; ?></b><br /><br />
    
  <table border="1" cellpadding="8">
  <tr>
    <th>No</th>
    <th>Nama</th>
    <th>Judul</th>
    <th>tanggal Rating</th>
    <th>Rating</th>
  </tr>
    <?php
    if( ! empty($transaksi)){
      $no = 1;
      foreach($transaksi as $data){
            $tgl = date('d-m-Y', strtotime($data->p_createat));
        echo "<tr>";
        echo "<td>".$no."</td>";
        echo "<td>".$data->p_nama."</td>";
        echo "<td>".$data->b_judul."</td>";
        echo "<td>".$tgl."</td>";
        echo "<td>".$data->r_rating."</td>";
        echo "</tr>";
        $no++;
      }
    }
    ?>
  </table>
</body>
</html>