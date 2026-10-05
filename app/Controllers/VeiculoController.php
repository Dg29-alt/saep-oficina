<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ClienteModel;
use App\Models\VeiculoModel;

// Vincula o Paciente com seu respectivo Responsavel (FK_RES_ID)
class VeiculoController extends BaseController
{
    // Exibe a listagem de paciente
    public function index()
    {
        // Instancia o Model de paciente
        $model = new VeiculoModel();

        // Verifica se o formulário de pesquisa foi enviado
        if ($this->request->getPost('botao_pesquisar')) {

            // Recupera o texto digitado pelo usuário
            $pesquisar = $this->request->getPost('pesquisar');

            // Busca os pacientes juntamente com o nome do responsavel
            // responsável por cada paciente
            $dados['veiculo'] = $model
                ->select('VEICULO.*, CLIENTE.CLI_NOME')

                // Relaciona PACIENTE com RESPONSAVEL pela chave estrangeira
                ->join(
                    'CLIENTE',
                    'CLIENTE.CLI_ID = VEICULO.FK_CLI_ID'
                )

                // Agrupa as condições da pesquisa
                ->groupStart()

                    // Pesquisa pela PLACA
                    ->like('VEI_PLACA', $pesquisar)

                    // Pesquisa pela MARCA
                    ->orLike('VEI_MARCA', $pesquisar)

                    // Pesquisa pelo ANO
                    ->orLike('VEI_ANO', $pesquisar)

                    // Também permite pesquisar pelo nome do responsavel
                    ->orLike('CLIENTE.CLI_NOME', $pesquisar)

                ->groupEnd()

                // Executa a consulta
                ->findAll();
        }
        else {

            // Caso não exista pesquisa, busca todos os pacientes
            // juntamente com o nome dos respectivos responsaveis
            $dados['veiculo'] = $model
                ->select('VEICULO.*, CLIENTE.CLI_NOME')
                ->join(
                    'CLIENTE',
                    'CLIENTE.CLI_ID = VEICULO.FK_CLI_ID'
                )
                ->findAll();
        }

        // Carrega a View de paciente
        return view('sistema/veiculo/index', $dados);
    }


    // Exibe o formulário para cadastrar um novo paciente
    public function novo()
    {
        // Instancia o Model de responsavel
        $veiculoModel = new VeiculoModel();

        // Busca todos os responsavel cadastrados
        // Esses dados serão utilizados em um campo SELECT
        $dados['veiculo'] = $veiculoModel->findAll();

        // Carrega o formulário de cadastro do veículo
        return view('sistema/veiculo/novo_veiculo', $dados);
    }


    // Insere um novo paciente
    public function inserir()
    {
        // Instancia o Model de paciente
        $model = new VeiculoModel();

        // Recupera os dados enviados pelo formulário
        $dados = [
            'VEI_PLACA' => $this->request->getPost('placa'),
            'VEI_MARCA' => $this->request->getPost('marca'),

            // Guarda o ID do responsavel escolhido no formulário
            // como chave estrangeira do paciente
            'FK_CLI_ID' => $this->request->getPost('cliente')
        ];

        // Insere o paciente no banco
        $model->insert($dados);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('veiculo'))
            ->with('success', 'Veiculo cadastrado com sucesso!');
    }


    // Exibe o formulário de edição
    public function editar($id)
    {
        // Instancia o Model de Paciente
        $veiculoModel = new VeiculoModel();

        // Instancia o Model de responsavel
        $clienteModel = new ClienteModel();

        // Busca o paciente que será editado
        $dados['veiculo'] = $veiculoModel->find($id);

        // Busca todos os responsáveis para preencher o SELECT
        $dados['cliente'] = $clienteModel->findAll();

        // Carrega a View de edição
        return view('sistema/veiculo/editar_veiculo', $dados);
    }


    // Atualiza um paciente
    public function atualizar($id)
    {
        // Instancia o Model
        $model = new VeiculoModel();

        // Recupera os novos dados do formulário
        $dados = [
            'VEI_PLACA' => $this->request->getPost('placa'),
            'VEI_MARCA' => $this->request->getPost('marca'),

            // Guarda o ID do responsavel escolhido no formulário
            // como chave estrangeira do paciente
            'FK_CLI_ID' => $this->request->getPost('cliente')
        ];

        // Atualiza o paciente pelo ID
        $model->update($id, $dados);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('veiculo'))
            ->with('success', 'Veiculo atualizado com sucesso!');
    }


    // Exclui um paciente
    public function excluir($id)
    {
        // Instancia o Model
        $model = new VeiculoModel();

        // Exclui o veículo pelo ID
        $model->delete($id);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('veiculo'))
            ->with('success', 'Veiculo excluído com sucesso!');
    }
}