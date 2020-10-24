<?php


namespace model;

use model\Cliente;
use model\Usuario;
use model\Mesa;

class Reserva{

    private $id;
    private $data_reserva;
    private $data_agendamento;
    private $mesa;
    private $usuario;
    private $cliente;

    public function __construct(){
        $this->mesa = new Mesa();
        $this->usuario = new Usuario();
        $this->cliente = new Cliente();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDataReserva()
    {
        return $this->data_reserva;
    }

    /**
     * @param mixed $data_reserva
     */
    public function setDataReserva($data_reserva)
    {
        $this->data_reserva = $data_reserva;
    }

    /**
     * @return mixed
     */
    public function getDataAgendamento()
    {
        return $this->data_agendamento;
    }

    /**
     * @param mixed $data_agendamento
     */
    public function setDataAgendamento($data_agendamento)
    {
        $this->data_agendamento = $data_agendamento;
    }

    /**
     * @return Mesa
     */
    public function getMesa()
    {
        return $this->mesa;
    }

    /**
     * @param Mesa $mesa
     */
    public function setMesa($mesa)
    {
        $this->mesa = $mesa;
    }

    /**
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param Usuario $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return Client
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * @param Client $cliente
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }


}