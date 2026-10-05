<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\ClienteModel;

class ClienteController extends BaseController
{
    // Exibe a listagem de responsavel
    public function index()
    {
        // Instancia o Model responsável pela tabela RESPONSAVEL
        $model = new ClienteModel();

        // Verifica se o formulário de pesquisa foi enviado
        if ($this->request->getPost('botao_pesquisar')) {

            // Recupera o texto digitado no campo de pesquisa
            $pesquisar = $this->request->getPost('pesquisar');

            // Busca responsaveis que possuem o termo informado
            // no nome ou data de nascimento
            $dados['cliente'] = $model
                ->like('CLI_NOME', $pesquisar)
                ->orLike('CLI_CPF', $pesquisar)
                ->findAll();
        }
        else {

            // Caso nenhuma pesquisa tenha sido realizada,
            // busca todos os responsaveis cadastrados
            $dados['cliente'] = $model->findAll();
        }

        // Carrega a View de listagem e envia os responsáveis encontrados
        return view('sistema/cliente/index', $dados);
    }


    // Exibe o formulário para cadastrar um novo responsavel
    public function novo()
    {
        // Apenas carrega a View com o formulário
        return view('sistema/cliente/novo_cliente');
    }


    // Insere um novo responsavel no banco de dados
    public function inserir()
    {
        // Instancia o Model
        $model = new ClienteModel();

        // Recupera os valores enviados pelo formulário
        $dados = [
            'CLI_NOME' => $this->request->getPost('nome'),
            'CLI_CPF' => $this->request->getPost('cpf')
        ];

        // Insere o novo responsavel no banco
        $model->insert($dados);

        // Redireciona para a listagem de responsavel
        return redirect()
            ->to(base_url('cliente'))
            ->with('success', 'Cliente cadastrado com sucesso!');
    }


    // Exibe o formulário para editar um responsavel
    public function editar($id)
    {
        // Instancia o Model
        $model = new ClienteModel();

        // Busca o responsavel pelo ID recebido na URL
        $dados['cliente'] = $model->find($id);

        // Carrega a View de edição enviando os dados do responsavel
        return view('sistema/cliente/editar_cliente', $dados);
    }


    // Atualiza os dados de um responsavel
    public function atualizar($id)
    {
        // Instancia o Model
        $model = new ClienteModel();

        // Recupera os novos valores enviados pelo formulário
        $dados = [
            'CLI_NOME' => $this->request->getPost('nome'),
            'CLI_CPF' => $this->request->getPost('cpf'),
            'CLI_TELEFONE' => $this->request->getPost('telefone')
        ];

        // Atualiza o registro correspondente ao ID
        $model->update($id, $dados);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('cliente'))
            ->with('success', 'Cliente atualizado com sucesso!');
    }


    // Exclui um responsavel
    public function excluir($id)
    {
        // Instancia o Model
        $model = new ClienteModel();

        // Exclui o registro correspondente ao ID
        $model->delete($id);

        // Redireciona para a listagem
        return redirect()
            ->to(base_url('cliente'))
            ->with('success', 'cliente excluído com sucesso!');
    }
}