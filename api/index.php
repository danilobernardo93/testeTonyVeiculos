<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'classes/Veiculo.php';

class Rest
{
    public static function getVeiculos($data)
    {
        $maximo = 3;
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
        $total = Veiculo::totalRegistro($parametros);
        
        $numPage = ceil($total['total']/$maximo);
        $paginas = '';

        for($i=1;$i<=$numPage;$i++)
        {
            $item = '<li class="page-item" onclick="buscaVeiculos('.$i.')"><a class="page-link" href="#">'.$i.'</a></li>';
            if($pagina == $i)
            {
                $item = '<li class="page-item active" ><a class="page-link" href="#">'.$i.'</a></li>';
            }
            $paginas.= $item;
        }
        
        if($retorno){
            return json_encode(array('status'=>'sucesso', 'dados' => $retorno, 'paginas'=>$paginas));
        }
        
        return json_encode(array('status'=>'erro', 'dados' => 'Nenhum veículo encontrado'));
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
        switch ($_GET['metodo']) {
            case 'getVeiculos':
                echo Rest::getVeiculos($_GET);
            break;
    
            default:
                echo json_encode(array('status'=>'erro', 'dados' => 'Médoto não encontrado'));
            break;
            
        }
    break;
        
    case 'POST':
        switch ($_POST['metodo']) 
        { 
            case 'adicionarVeiculo':
                echo Rest::adicionarVeiculo($_POST);
            break;

            default:
                echo json_encode(array('status'=>'erro', 'dados' => $_POST));
            break;
        }
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