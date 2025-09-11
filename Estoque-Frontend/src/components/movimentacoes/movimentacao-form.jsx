import React from 'react';
import { useState } from "react";
import api from "../../services/api";
import { Row, Col, Button, Form, Card } from "react-bootstrap";
import { FaSearch } from "react-icons/fa";

export default function MovimentacaoForm({ onSearch, showToast }) {
  const [form, setForm] = useState({
    bebida: "",
    tipo: "entrada",
  });

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      let url = form.bebida ? `/movimentacao/bebida/${form.bebida}/getByID?excluido=0` : "/movimentacoes?excluido=0";
      let payload = {};
      if (form.tipo) {
        payload.tipo = form.tipo;
      }
      if(form.bebida) {
        payload.bebida = form.bebida;
      }
      const res = await api.get(url, { params: payload });

      onSearch(res.data);
    } catch (err) {
      let mensagemErro = err.response?.data || "Erro desconhecido";
      if (typeof mensagemErro === "object" && mensagemErro !== null) {
        mensagemErro = mensagemErro.erro || JSON.stringify(mensagemErro);
      }
      showToast(mensagemErro, "danger");
    }
  };

  return (
    <Card className="shadow-sm border-0 mb-4">
      <Card.Body>
        <h4 className="mb-4"><FaSearch /> Buscar Movimentações</h4>
        <Form onSubmit={handleSubmit}>
          <Row className="g-3">
            <Col lg={6} md={6}>
              <Form.Group>
                <Form.Label>Bebida</Form.Label>
                <Form.Control
                  type="text"
                  placeholder="ID ou nome da bebida"
                  value={form.bebida}
                  onChange={(e) =>
                    setForm({ ...form, bebida: e.target.value })
                  }
                />
              </Form.Group>
            </Col>

            <Col lg={6} md={6}>
              <Form.Group>
                <Form.Label>Tipo</Form.Label>
                <Form.Select
                  value={form.tipo}
                  onChange={(e) =>
                    setForm({ ...form, tipo: e.target.value })
                  }
                >
                  <option value="">Todos</option>
                  <option value="entrada">Entrada</option>
                  <option value="saida">Saída</option>
                </Form.Select>
              </Form.Group>
            </Col>
          </Row>

          <div className="d-flex justify-content-end mt-4">
            <Button type="submit" variant="primary">
              <FaSearch /> Buscar
            </Button>
          </div>
        </Form>
      </Card.Body>
    </Card>
  );
}
