<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabela INSS</title>

    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <h1>Calcular Salário Líquido</h1>
    <form class="calculo" action="" method="post">
      <input type="text" name="nome" id="" required placeholder="  Nome">
      <input type="text" name="salariobruto" id="" required placeholder="  Salário Bruto">
      <input type="number" name="dependentes" id="" required placeholder="  Nº de Dependentes">
      <button type="submit" name="Enviar">Enviar</button>
    </form>

    <?php
      error_reporting(0);
      ini_set(“display_errors”, 0 );
      $c=0;
      $e=0;
    ?>

  <?php
    if(isset($_POST['Enviar'])&&$_POST['nome']!=NULL){
      $nome = $_POST['nome'];
      $salariobruto = $_POST['salariobruto'];
      $dependentes = $_POST['dependentes'];

      if($salariobruto <= 1045.00){
        $aliquotaINSS = 7.5;
      }

      else if($salariobruto >= 1045.01 && $salariobruto <= 2089.60){
        $aliquotaINSS = 9;
      }

      else if($salariobruto >= 2089.61 && $salariobruto <= 3134.40){
        $aliquotaINSS = 12;
      }

      else if($salariobruto >= 3134.41 && $salariobruto <= 6101.06){
        $aliquotaINSS = 14;
      }

      $brutoINSS = $salariobruto - (($aliquotaINSS * $salariobruto) / 100);

      if($brutoINSS <= 1903.98){
        $aliquota = 0;
        $parcela = 0;
      }

      else if($brutoINSS >= 1903.99 && $brutoINSS <= 2826.65){
        $aliquota = 7.5;
        $parcela = 142.80;
      }

      else if($brutoINSS >= 2826.66 && $brutoINSS <= 3751.05){
        $aliquota = 15;
        $parcela = 354.80;
      }

      else if($brutoINSS >= 3751.06 && $brutoINSS <= 4664.68){
        $aliquota = 22.5;
        $parcela = 636.13;
      }

      else if($brutoINSS > 1903.98){
        $aliquota = 27.5;
        $parcela = 869.36;
      }

      $liquido = $brutoINSS - ((($aliquota * $brutoINSS) / 100) - $parcela);

      $dependentes=48.62*$dependentes;
      if ($liquido<=1425.56) {
        $liquido=$liquido + $dependentes;
      }
      else {
        $dependentes=0;
      }

      $candido = "$nome|$salariobruto|$aliquotaINSS|$aliquota|$parcela|$dependentes|$liquido\n";

      $arquivo = fopen('salarioliq.txt','a+');
      fwrite($arquivo, $candido);
      fclose($arquivo);

    $arquivo = fopen("salarioliq.txt",'r');

    while(true) {
      $valores[$e] = fgets($arquivo);
      $valoresT = explode('|',$valores[$e]);

      $nomeT[$c]=$valoresT[0];
      $brutoT[$c]=$valoresT[1];
      $alINSS[$c]=$valoresT[2];
      $alIRRF[$c]=$valoresT[3];
      $parT[$c]=$valoresT[4];
      $depT[$c]=$valoresT[5];
      $liqT[$c]=$valoresT[6];

      $brutoT[$c] = number_format($brutoT[$c], 2, '.', '');
      $alINSS[$c] = number_format($alINSS[$c], 2, '.', '');
      $alIRRF[$c] = number_format($alIRRF[$c], 2, '.', '');
      $parT[$c] = number_format($parT[$c], 2, '.', '');
      $depT[$c] = number_format($depT[$c], 2, '.', '');
      $liqT[$c] = number_format($liqT[$c], 2, '.', '');

      if ($valores[$e] == null) break;
      $e++;
      $c++;
 }
 fclose($arquivo);
}



  ?>

    <table class="tabelinha">
      <tr>
        <th class="tit">NOME</th>
        <th class="tit">SALÁRIO BRUTO</th>
        <th class="tit">% INSS</th>
        <th class="tit">% IRPF</th>
        <th class="tit">PARCELA IRPF</th>
        <th class="tit">$ DOS DEPENDENTES</th>
        <th class="tit">SALÁRIO LÍQUIDO</th>
      </tr>

      <?php
      if ($_POST['nome']!=NULL) {

        for ($i=0; $i < $c; $i++) {
          echo "<tr><th>$nomeT[$i]</td><td>R$$brutoT[$i]</td><td>$alINSS[$i]</td><td>R$$alIRRF[$i]</td><td>R$$parT[$i]</td><td>R$$depT[$i]</td><td>R$$liqT[$i]</td><tr>";
        }

        $c = $c + 1;
      }
    ?>

    </table>

</body>
</html>
