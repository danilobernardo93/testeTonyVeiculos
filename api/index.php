<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'classes/Veiculo.php';

class Rest
{
    public static function getVeiculos($data)
    {
        $maximo = 10;
        $pagina = isset($_GET['pagina']) ? ($_GET['pagina']) : '1'; 
        $inicio = $pagina - 1;
        $inicio = $maximo * $inicio;

        $parametros = array();
        $paramAltorizados = ['id','ano','marca','veiculo','descricao'];
        
        foreach($data as $key => $value )
        {
            if(in_array($key, $paramAltorizados))
            {
                $parametros[$key] = str_replace("-"," ",$value);
            }
        }

        $retorno = Veiculo::busca($parametros,$inicio,$maximo);
        $total   = Veiculo::totalRegistro($parametros);
        
        $numPage = ceil($total['total']/$maximo);

        if($retorno){
            return json_encode(array('status'=>'sucesso', 'dados' => $retorno, 'numPaginas'=>$numPage,'porPagina'=>$maximo));
        }
        
        return json_encode(array('status'=>'erro', 'dados' => 'Nenhum veículo encontrado'));
    }

    public static function getVeiculo($id)
    {

        

        $retorno = is_numeric($_GET['id']) ? Veiculo::getVeiculo($id) : false;
        

        if($retorno){
            return json_encode(array('status'=>'sucesso', 'dados' => $retorno));
        }
        
        return json_encode(array('status'=>'erro', 'dados' => 'Nenhum veículo encontrado'));
    }
    
    public static function marcasModelos($data)
    {
        $marcas  = Veiculo::getMarcasModelos($data['filtro']);
        return json_encode(array('marcasModelos'=>$marcas,'filtro'=>$data['filtro']));
    }    

    public static function atualizaVeiculo($data)
    {
        $permitidos = ['ano','marca','veiculo','descricao','vendido'];
        $parametros = array();
        $id = $data['id'];
        
        foreach($data as $key => $value )
        {
            if(in_array($key, $permitidos))
            {
                $parametros[$key] = ($value);
            }
        }
        date_default_timezone_set('America/Sao_Paulo');
        $parametros['updated'] = date("Y-m-d H-i-s");

        $retorno = Veiculo::atualiza($parametros, $id);

        if($retorno)
        {
            return json_encode(array('status'=>'sucesso', 'dados' => 'Dados atualizados'));
        }        

        return json_encode(array('status'=>'erro', 'dados' => 'Dados não atualizado'));
    }

    public static function adicionarVeiculo($data)
    {
        $permitidos = ['ano','marca','veiculo','descricao','vendido'];
        $parametros = array();

        foreach($data as $key => $value )
        {
            if(in_array($key, $permitidos))
            {
                $parametros[$key] = ($value);
            }
        }

        $retorno = Veiculo::adicionar($parametros);

        if($retorno)
        {
            return json_encode(array('status'=>'sucesso', 'dados' => 'Veiculo adicionado'));
        }        

        return json_encode(array('status'=>'erro', 'dados' => 'Erro ao adicionar veículo'));
    }

    public static function deletarVeiculo($data)
    {
        $parametros["id"] = $data["id"];

        $retorno = Veiculo::deletar($parametros);

        if($retorno)
        {
            return json_encode(array('status'=>'sucesso', 'dados' => 'Veiculo deletado'));
        }        

        return json_encode(array('status'=>'erro', 'dados' => 'Erro ao deletar veículo'));
    }
}


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if(isset($_GET['marcasModelos']))
        {
            echo Rest::marcasModelos($_GET);
        }
        else if(isset($_GET['id']))
        {
            echo Rest::getVeiculo($_GET['id']);
        }
        else{
            echo Rest::getVeiculos($_GET);
        }
    break;
        
    case 'POST':
        echo Rest::adicionarVeiculo($_REQUEST);
    break;

    case 'PUT':
        echo Rest::atualizaVeiculo($_REQUEST);
    break;

    case 'DELETE':
        echo Rest::deletarVeiculo($_REQUEST);
    break;
    
    default:
        echo json_encode(array('status'=>'erro', 'dados' => 'Médoto não autorizado'));
        break;
}