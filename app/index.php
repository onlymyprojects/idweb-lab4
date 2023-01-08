<?php

session_start();

require __DIR__ . '/src/db/dbcon.php';

$sql = 'SELECT * FROM crypto';

$query_run = $conn->query($sql);

class stackingCalc
{
    private $amount, $apy, $period;

    public function __construct($amount, $apy, $period)
    {
        $this->amount = $amount;
        $this->apy = $apy;
        $this->period = $period;
    }

    public function fixed()
    {
        $result = $this->amount;

        for ($i = 0; $i < $this->period; $i++) {
            $result += $result * ($this->apy / 100);
        }

        return $result;
    }

    public function flexible()
    {
        $result = $this->amount;

        for ($i = 0; $i < $this->period * 365; $i++) {
            $result += $result * (($this->apy / 100) / 365);
        }

        return $result;
    }
}

class passwordGenerator
{
    private $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function password()
    {
        $this->password[0] = strtoupper($this->password[0]);

        $symbols = ['&', '%', '~'];

        $i = 0;

        do {
            $positionsForSymbols = [0, strlen($this->password) - 1, strlen($this->password) / 2];
            $this->password = substr_replace($this->password, $symbols[rand(0, 2)], $positionsForSymbols[$i], 0);
            $i++;
        } while ($i < count($positionsForSymbols));

        return $this->password;
    }
}

$result1 = '-';
$result2 = '-';
$result3 = '-';

$amount = '';
$apy = '';
$period = '';
$password = '';

if (isset($_POST['amount']) && isset($_POST['apy']) && isset($_POST['period'])) {
    $amount = $_POST['amount'];
    $apy = $_POST['apy'];
    $period = $_POST['period'];
    $stacking = new stackingCalc($_POST['amount'], $_POST['apy'], $_POST['period']);
    $result1 = round($stacking->fixed(), 2);
    $result2 = round($stacking->flexible(), 2);
} else if (isset($_POST['password'])) {
    $password = $_POST['password'];
    $newPassword = new passwordGenerator($_POST['password']);
    $result3 = $newPassword->password();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="src/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <h1>
                <hr>
                Average APY values for available crypto
                <hr>
            </h1>

            <?php
            if (mysqli_num_rows($query_run) > 0) {
                foreach ($query_run as $product) {
            ?>
                    <div class="col-xl-3 col-lg-4 col-sm-6 col-xs-12">
                        <div class=" block">
                            <?php echo '<img src="data:image/jpeg;base64,' . base64_encode($product['img']) . '"/>' ?>
                            <h2><?= $product['title']; ?></h2>
                            <h3><?= $product['apy']; ?>%</h3>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<h5> No Record Found </h5>";
            }
            ?>

            <h1 id="calculator">
                <hr>
                Investment calculator
                <hr>
            </h1>

            <div class="d-flex justify-content-center">
                <div class="col-xl-6 col-xs-12">
                    <form action="#calculator" method="post">
                        <div class="col-xl-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Amount ($)</span>
                                </div>
                                <input type="text" name="amount" required value="<?= $amount; ?>" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">APY (%)</span>
                                </div>
                                <input type="text" name="apy" required value="<?= $apy; ?>" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Period (years)</span>
                                </div>
                                <input type="text" name="period" required value="<?= $period; ?>" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">
                            Calculate
                        </button>
                    </form>

                    <hr>

                    <h4>Staking:</h4>
                    <h5>Fixed (locked) <span class="number"><?= $result1; ?><span>$</span></span></h5>
                    <h5>Flexible (auto-investment) <span class="number"><?= $result2; ?><span>$</span></span></h5>
                </div>
            </div>

            <h1 id="password">
                <hr>
                Very useful thing !!!
                <hr>
            </h1>

            <h4>To keep your money in safety, you must set a strong password
                to your wallets. So, we can help you in that. Just invent a password
                (that is strong from your point of view) and we will make it much stronger.
            </h4>

            <div class="d-flex justify-content-center">
                <div class="col-xl-6 col-xs-12">
                    <br>
                    <form action="#password" method="POST">
                        <div class="col-xl-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Your Password</span>
                                </div>
                                <input type="text" name="password" required value="<?= $password; ?>" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">
                            Generate
                        </button>
                    </form>

                    <hr>

                    <h4>Your new password:</h4>
                    <h5><span class="number"><?= $result3; ?></span></h5>
                </div>
            </div>

            <!-- <h1>
                <hr id="mail">
                Have any questions ???
                <hr>
            </h1>

            <div class="d-flex justify-content-center">
                <div class="col-xl-6 col-xs-12">
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Well done!</h4>
                        <p>Your message was send successfully.</p>
                        <hr>
                        <p class="mb-0">We'll contact you as soon as possible.</p>
                    </div>

                    <form id="contact">
                        <div class="col-xl-12">
                            <input type="hidden" name="project_name" value="CryptoProject">
                            <input type="hidden" name="admin_email" value="johnfeed500@gmail.com">
                            <input type="hidden" name="form_subject" value="Mail was sent from CryptoProject">

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Your Name</span>
                                </div>
                                <input type="text" name="name" class="form-control" required autocomplete="off" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Your Email</span>
                                </div>
                                <input type="email" name="email" class="form-control" required autocomplete="off" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-default">Your Message</span>
                                </div>
                                <input type="text" name="message" class="form-control" required autocomplete="off" aria-label="Default" aria-describedby="inputGroup-sizing-default">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">
                            Send
                        </button>
                    </form>
                </div>
            </div> -->

            <div id="disqus_thread" style="margin-top: 50px; background: #E5E5E5; padding-top: 30px;"></div>
            <script>
                /**
                 *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                 *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
                /*
                var disqus_config = function () {
                this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                };
                */
                (function() { // DON'T EDIT BELOW THIS LINE
                    var d = document,
                        s = d.createElement('script');
                    s.src = 'https://crypto-project.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
</body>

</html>