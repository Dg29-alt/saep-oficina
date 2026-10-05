<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\AgendamentoModel;
use App\Models\VeiculoModel;
use App\Models\ClienteModel;

// Vincula o Agendamento ao Paciente (FK_PAC_ID) com seu respectivo Responsável (FK_RES_ID)
class AgendamentoController extends BaseController
{
    // Exibe a listagem de agendamentos
    public function index()
    {
        // Instancia o Model de agendamento
        $model = new AgendamentoModel();

        // Verifica se o formulário de pesquisa foi enviado
        if ($this->request->getPost('botao_pesquisar')) {

            // Recupera o termo digitado
            $pesquisar = $this->request->getPost('pesquisar');

            // Busca os agendamentos juntamente com
            // informações do paciente e do responsável
            $dados['agendamento'] = $model
                ->select(
                    'AGENDAMENTOS.*,
                    VEICULO.VEI_NOME,
                    CLIENTE.CLI_NOME'
                )

                // Relaciona o agendamento ao paciente
                ->join(
                    'VEICULO',
                    'VEICULO.VEI_ID = AGENDAMENTOS.FK_VEI_ID'
                )

                // Relaciona o agendamento ao responsável
                ->join(
                    'CLIENTE',
                    'CLIENTE.CLI_ID = AGENDAMENTOS.FK_CLI_ID'
                )

                // Agrupa as condições utilizadas na pesquisa
                ->groupStart()

                    // Pesquisa pelo nome do paciente
                    ->like('VEICULO.VEI_PLACA', $pesquisar)

                    // Pesquisa pelo nome do responsável
                    ->orLike('CLIENTE.CLI_PLACA', $pesquisar)

                    // Pesquisa pelo motivo
                    ->orLike('AGE_SERVICO', $pesquisar)

                    // Pesquisa pelo status
                    ->orLike('AGE_STATUS', $pesquisar)

                ->groupEnd()

                // Ordena os agendamentos pela data e hora
                ->orderBy('AGE_DATA_HORA', 'ASC')

                // Executa a consulta
                ->findAll();
        }
        else {

            // Caso nenhuma pesquisa tenha sido realizada,
            // busca todos os agendamentos
            $dados['agendamento'] = $model
                ->select(
                    'AGENDAMENTO.*,
                    VEICULO.VEI_PLACA,
                    CLIENTE.CLI_NOME'
                )

                // Relaciona o paciente ao agendamento
                ->join(
                    'VEICULO',
                    'VEICULO.VEI_ID = AGENDAMENTO.FK_VEI_ID'
                )

                // Relaciona o responsável ao agendamento
                ->join(
                    'CLIENTE',
                    'CLIENTE.CLI_ID = AGENDAMENTO.FK_CLI_ID'
                )

                // Ordena pela data e hora do agendamento
                ->orderBy('AGE_DATA_HORA', 'ASC')

                // Executa a consulta
                ->findAll();
        }

        // Carrega a View com os agendamentos encontrados
        return view('sistema/agendamento/index', $dados);
    }


    // Exibe o formulário para cadastrar um novo agendamento
    public function novo()
    {
        // Instancia o Model de paciente
        $veiculoModel = new VeiculoModel();

        // Instancia o Model de responsável
        $clienteModel = new ClienteModel();

        // Busca todos os pacientes para preencher o SELECT
        $dados['veiculo'] = $veiculoModel->findAll();

        // Busca todos os responsáveis para preencher o SELECT
        $dados['cliente'] = $clienteModel->findAll();

        // Carrega o formulário
        return view(
            'sistema/agendamento/novo_agendamento',
            $dados
        );
    }


    // Insere um novo agendamento
    public function inserir()
    {
        // Instancia o Model
        $model = new AgendamentoModel();

        // Recupera os dados enviados pelo formulário
        $dados = [
            'AGE_DATA_HORA' => $this->request->getPost('data_hora'),
            'AGE_SERVICO' => $this->request->getPost('servico'),
            'AGE_STATUS' => $this->request->getPost('status'),

            // Paciente escolhido no formulário
            'FK_VEI_ID' => $this->request->getPost('veiculo'),

            // Responsável escolhido no formulário
            'FK_CLI_ID' => $this->request->getPost('cliente')
        ];

        // Insere o agendamento no banco
        $model->insert($dados);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('agendamento'))
            ->with('success', 'Agendamento cadastrado com sucesso!');
    }


    // Exibe o formulário de edição
    public function editar($id)
    {
        // Instancia os três Models necessários
        $agendamentoModel = new AgendamentoModel();
        $veiculoModel = new VeiculoModel();
        $clienteModel = new ClienteModel();

        // Busca o agendamento pelo ID
        $dados['agendamento'] = $agendamentoModel->find($id);

        // Busca os pacientes para preencher o SELECT
        $dados['veiculo'] = $veiculoModel->findAll();

        // Busca os responsáveis para preencher o SELECT
        $dados['cliente'] = $clienteModel->findAll();

        // Carrega a View de edição
        return view(
            'sistema/agendamento/editar_agendamento',
            $dados
        );
    }


    // Atualiza um agendamento
    public function atualizar($id)
    {
        // Instancia o Model
        $model = new AgendamentoModel();

        // Recupera os novos valores enviados pelo formulário
        $dados = [
            'AGE_DATA_HORA' => $this->request->getPost('data_hora'),
            'AGE_SERVICO' => $this->request->getPost('servico'),
            'AGE_STATUS' => $this->request->getPost('status'),
            'FK_VEI_ID' => $this->request->getPost('veiculo'),
            'FK_CLI_ID' => $this->request->getPost('cliente')
        ];

        // Atualiza o agendamento no banco
        $model->update($id, $dados);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('agendamento'))
            ->with('success', 'Agendamento atualizado com sucesso!');
    }


    // Exclui um agendamento
    public function excluir($id)
    {
        // Instancia o Model
        $model = new AgendamentoModel();

        // Exclui o agendamento pelo ID
        $model->delete($id);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('agendamento'))
            ->with('success', 'Agendamento excluído com sucesso!');
    }
}