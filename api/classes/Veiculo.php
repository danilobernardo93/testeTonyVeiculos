<?php

class Veiculo
{
    public function busca($parametros, $inicio=0, $maximo=30)
    {
        $con = new PDO('mysql: host=localhost; dbname=tony;','root','');

        $param = count($parametros)  > 0 ? ' WHERE id > 0 ' : '';
        foreach($parametros as $key => $value)
        {
            $param.= $key == 'veiculo' ? " and $key  LIKE '%$value%' " : " and $key  = '$value' ";
        }
        
        $sql = "SELECT * FROM veiculos $param ORDER BY veiculo ASC LIMIT $inicio,$maximo";
        $sql = $con->prepare($sql);
        
        $sql->execute();

        $resultados = array();

        while($row =  $sql->fetch(PDO::FETCH_ASSOC))
        {
            $resultados[] = $row;
        }

        return $resultados;
    }

    public function totalRegistro($parametros)
    {
        $con = new PDO('mysql: host=localhost; dbname=tony;','root','');

        $param = count($parametros)  > 0 ? ' WHERE id > 0 ' : '';
        foreach($parametros as $key => $value)
        {
            $param.= $key == 'veiculo' ? " and $key  LIKE '%$value%' " : " and $key  = '$value' ";
        }
        
        $sql = "SELECT count(*) as total FROM veiculos $param ";
        $sql = $con->prepare($sql);
        
        $sql->execute();

        $resultados = '';

        while($row =  $sql->fetch(PDO::FETCH_ASSOC))
        {
            $resultados = $row;
        }

        return $resultados;
    }

    public function atualiza($data,$id)
    {
        $con = new PDO('mysql: host=localhost; dbname=tony;','root','');

        $campos = '';
        foreach($data as $key => $value)
        {
            $campos.= ' '.$key.' = :'.$key.',';
        }
        
        $data['id'] = $id;
        $campos = substr($campos,0,-1);
        
        $sql = "UPDATE veiculos SET $campos WHERE id = :id ";
        $stmt = $con->prepare($sql);
        $stmt->execute($data);
        if($stmt->rowCount())
        {
            return true;
        }
        return false;
    }

    public function adicionar($data)
    {
        $con = new PDO('mysql: host=localhost; dbname=tony;','root','');

        $fields = '';
        $fields2 = '';
        foreach($data as $key => $value)
        {
            $fields.= $key.',';
            $fields2.= ':'.$key.',';
        }
        
        $fields = substr($fields,0,-1);
        $fields2 = substr($fields2,0,-1);
        
        $sql = "INSERT INTO veiculos ($fields) VALUES ($fields2) ";
        
        $stmt = $con->prepare($sql);
        $stmt->execute($data);

        if($stmt->rowCount())
        {
            return true;
        }
        return false;
    }

    public function deletar($data)
    {
        $con = new PDO('mysql: host=localhost; dbname=tony;','root','');
        $sql = "DELETE FROM veiculos WHERE id = :id ";
        $stmt = $con->prepare($sql);
        $stmt->execute($data);

        if($stmt->rowCount())
        {
            return true;
        }
        return false;
    }
}