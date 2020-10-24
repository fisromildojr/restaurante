<?php

namespace controller;

include_once "Conexao.php";

use model\Reserva;

class ReservaController {

    private static $instance;
    private $conexao;

    private function __construct(){
        $this->conexao = Conexao::getInstance();
    }

    public static function getInstance(){
        if (self::$instance == null){
            self::$instance = new ReservaController();
        }

        return self::$instance;
    }

    private function exists(Reserva $reserva){
        $sql = "SELECT * FROM reserva WHERE id_mesa = :id_mesa AND data_reserva = :data_reserva";
        $p_sql = $this->conexao->prepare($sql);
        $p_sql->bindValue(":id_mesa", $reserva->getMesa()->getId());
        $p_sql->bindValue(":data_reserva", $reserva->getDataReserva());
        $p_sql->execute();
        $retornoSQL = $p_sql->fetchAll(\PDO::FETCH_ASSOC);

        if(empty($retornoSQL))
            return false;

        return true;
    }

    private function inserir(Reserva $reserva){
        $sql = "INSERT INTO reserva (id_cliente, id_mesa, id_usuario, data_reserva, data_agendamento) VALUES 
                (:id_cliente, :id_mesa, :id_usuario, :data_reserva, NOW())";

        $p_sql = $this->conexao->prepare($sql);
        $p_sql->bindValue(":id_cliente", $reserva->getCliente()->getId());
        $p_sql->bindValue(":id_mesa", $reserva->getMesa()->getId());
        $p_sql->bindValue(":id_usuario", $reserva->getUsuario()->getId());
        $p_sql->bindValue(":data_reserva", $reserva->getDataReserva());

        return $p_sql->execute();
    }

    private function alterar(Reserva $reserva){
        $sql = "UPDATE reserva SET id_cliente = :id_cliente, id_mesa = :id_mesa, id_usuario = :id_usuario, data_reserva = :data_reserva
                WHERE id_cliente = :id_cliente AND data_reserva = :data_reserva";

        $p_sql = $this->conexao->prepare($sql);
        $p_sql->bindValue(":id_cliente", $reserva->getCliente()->getId());
        $p_sql->bindValue(":id_mesa", $reserva->getMesa()->getId());
        $p_sql->bindValue(":id_usuario", $reserva->getUsuario()->getId());
        $p_sql->bindValue(":data_reserva", $reserva->getDataReserva());

        return $p_sql->execute();
    }

    public function gravar(Reserva $reserva){
        if ($this->exists($reserva)){
            return false;
        }else{
            return $this->inserir($reserva);
        }
    }

    public function retornaTodos(){
        $lstReservas = array();
        $sql = "SELECT * FROM reserva ORDER BY data_reserva";
        $p_sql = $this->conexao->query($sql, \PDO::FETCH_ASSOC);
        foreach ($p_sql as $row){
            $reserva = $this->preencherDados($row);
            $lstReservas[] = $reserva;
        }

        return $lstReservas;
    }

    public function retornaFiltro($data_filtro){
        $lstReservas = array();
        $sql = "SELECT cliente.nome as cliente, mesa.descricao as mesaDescricao, mesa.lugares as mesaLugares, 
                mesa.posicao as mesaPosicao, usuario.nome as usuario, 
                DATE_FORMAT(reserva.data_reserva, '%d/%m/%Y') as data_reserva,
                DATE_FORMAT(reserva.data_agendamento, '%d/%m/%Y') as data_agendamento        
                FROM reserva
                JOIN cliente ON(reserva.id_cliente = cliente.id)
                JOIN mesa ON(reserva.id_mesa = mesa.id)
                JOIN usuario ON(reserva.id_usuario = usuario.id)
                WHERE data_reserva = :data_filtro ORDER BY cliente";

        $p_sql = $this->conexao->prepare($sql);
        $p_sql->bindValue(":data_filtro", $data_filtro);
        $p_sql->execute();

        $reservas = $p_sql->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($reservas as $row){
            $reserva = $this->preencherDados($row);
            $lstReservas[] = $reserva;
        }

        return $lstReservas;
    }

    public function buscarReserva($data_filtro){
        $lstReserva = Array();
        $sql = "SELECT cliente.nome as cliente, mesa.descricao as mesaDescricao, mesa.lugares as mesaLugares, 
                mesa.posicao as mesaPosicao, usuario.nome as usuario, 
                DATE_FORMAT(reserva.data_reserva, '%d/%m/%Y') as data_reserva,
                DATE_FORMAT(reserva.data_agendamento, '%d/%m/%Y') as data_agendamento        
                FROM reserva
                JOIN cliente ON(reserva.id_cliente = cliente.id)
                JOIN mesa ON(reserva.id_mesa = mesa.id)
                JOIN usuario ON(reserva.id_usuario = usuario.id)
                WHERE data_reserva = :data_filtro ORDER BY cliente";
        $p_sql = $this->conexao->prepare($sql);
//        $p_sql->bindValue(":id_mesa", $id_mesa);
        $p_sql->bindValue(":data_filtro", $data_filtro);
        $p_sql->execute();
        $retornoSQL = $p_sql->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($retornoSQL as $row){
            $reserva = $this->preencherDados($row);
            $lstReserva[] = $reserva;
        }

        return $reserva;
    }

    private function preencherDados($row){
        $reserva = new Reserva();
        $reserva->getCliente()->setNome($row['cliente']);
        $reserva->getMesa()->setDescricao($row['mesaDescricao']);
        $reserva->getMesa()->setPosicao($row['mesaPosicao']);
        $reserva->getMesa()->setLugares($row['mesaLugares']);
        $reserva->getUsuario()->setNome($row['usuario']);
        $reserva->setDataReserva($row['data_reserva']);
        $reserva->setDataAgendamento($row['data_agendamento']);

        return $reserva;
    }

    public function excluir($id_mesa, $data_reserva){
        $p_sql = "DELETE FROM reserva WHERE id_mesa = :id_mesa AND data_reserva = :data_reserva";
        $p_sql->bindValue(":id_mesa", $id_mesa);
        $p_sql->bindValue(":data_reserva", $data_reserva);

        return $p_sql->execute();
    }

}