<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Novo Veiculo</title>
    </head>
    <body>
        <h1>Novo Veiculo</h1>
        <form action="<?= base_url('veiculo/inserir') ?>" method="POST">
            <label>Nome:</label><br>
            <input type="text" id="placa" name="placa" placeholder="Placa..." required>

            <br><br>
            
            <label>marca:</label><br>
            <input type="date" id="marca" name="marca" required>

            <br><br>

            <label>Veiculo:</label><br>
            <select id="veiculo" name="veiculo" required>
                <option value="">Selecione um veiculo</option>
                <?php foreach($veiculo as $vei): ?>
                    <option value="<?= $vei['VEI_ID'] ?>">
                        <?= $vei['VEI_PLACA'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <br><br>

            <input type="submit" id="cadastrar_veiculo" name="cadastrar_veiculo" value="Cadastrar">
        </form>
        <br>
        <a href="<?= base_url('veiculo') ?>"><button>Voltar</button></a>
    </body>
</html>