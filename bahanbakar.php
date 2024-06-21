<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Purchase</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            background-color: #F8DE22;
        }
        header {
            background-color: #C70039;
            margin: 0;
            padding: 20px 0;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        h1 {
            text-align: center;
            color: white;
            margin-left: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
            background-color: #f4f4f4;
        }
        form {
            width: 400px;
            margin: 100px auto;
            background-color: #F94C10;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-top: 20px;
            font-size: 18px;
            color: white;
            text-align: center;
        }
        select, input[type="number"], input[type="text"] {
            width: 100%;
            padding: 15px;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 18px;
            margin-top: 10px;
            background-color: #F8DE22;
        }
        .input {
            max-width: 100%;
        }
        .input:focus {
            box-shadow: 2px 2px 5px black, inset 2px 2px 5px black, -1px -1px 5px rgba(68, 67, 67, 0.781), inset -1px -1px 5px rgba(66, 65, 65, 0.822);
        }
        button {
            width: 100%;
            padding: 15px;
            background-color: #C70039;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #900C3F;
        }
        .receipt-table {
            width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-size: 18px;
        }
        .receipt-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .receipt-table th, .receipt-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .receipt-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php
        class Shell {
            public $price;
            public $quantity;
            public $type;
            public $ppn;

            public function __construct($price, $type) {
                $this->price = $price;
                $this->type = $type;
                $this->ppn = 10 / 100 * $price; // PPN calculation
            }

            public function getTotalPrice() {
                return $this->price + $this->ppn;
            }
        }

        class Beli extends Shell {
            public $receipt;

            public function __construct($price, $type, $quantity) {
                parent::__construct($price, $type);
                $this->quantity = $quantity;
                $this->receipt = "Anda membeli bahan bakar minyak tipe {$type} dengan jumlah: {$quantity} liter. Total yang harus Anda bayar adalah Rp. " . number_format($this->getTotalPrice() * $quantity, 0, ',', '.');
            }

            public function getReceiptDetails() {
                return [
                    'Jenis Bahan Bakar' => $this->type,
                    'Harga per Liter' => number_format($this->price, 0, ',', '.'),
                    'PPN per Liter' => number_format($this->ppn, 0, ',', '.'),
                    'Jumlah Liter' => $this->quantity,
                    'Total Bayar' => number_format($this->getTotalPrice() * $this->quantity, 0, ',', '.')
                ];
            }
        }

        $receiptDetails = [];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $liter = $_POST["liter"];
            $fuelType = $_POST["fuel-type"];

            $prices = [
                "Shell Super" => 15420,
                "SVPowerDiesel" => 16130,
                "Shell V-Power Diesel" => 18310,
                "Shell V-Power Nitro" => 16510
            ];

            $price = $prices[$fuelType];
            $fuel = new Beli($price, $fuelType, $liter);
            $receiptDetails = $fuel->getReceiptDetails();
        }
    ?>
    <header>
        <img src="shell.png" alt="Shell Logo" width="50" height="50">
        <h1>SHELL</h1>
    </header>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="liter">Masukkan jumlah (liter):</label>
        <input class="input" placeholder="Masukkan Jumlah..." name="liter" type="text" required />

        <label for="fuel-type">Pilih Tipe Bahan Bakar:</label>
        <select id="fuel-type" name="fuel-type" required>
            <option value="" disabled selected hidden>Pilih Jenis Shell</option>
            <option value="Shell Super">Shell Super</option>
            <option value="SVPowerDiesel">SVPowerDiesel</option>
            <option value="Shell V-Power Diesel">Shell V-Power Diesel</option>
            <option value="Shell V-Power Nitro">Shell V-Power Nitro</option>
        </select>

        <button type="submit">Beli</button>
    </form>

    <?php if (!empty($receiptDetails)) : ?>
    <div class="receipt-table">
        <table>
            <tr>
                <th>Deskripsi</th>
                <th>Detail</th>
            </tr>
            <?php foreach ($receiptDetails as $key => $value) : ?>
            <tr>
                <td><?php echo htmlspecialchars($key); ?></td>
                <td><?php echo htmlspecialchars($value); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</body>
</html>
