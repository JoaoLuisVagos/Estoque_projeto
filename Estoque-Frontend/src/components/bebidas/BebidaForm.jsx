import { useState, useEffect } from "react";
import api from "../../services/api";
import { Row, Col, Button, Form, Card } from "react-bootstrap";

export default function BebidaForm({ onSave, showToast, bebida }) {
  const [form, setForm] = useState({
    nome: "",
    tipo: "alcoolica",
    volume: "",
    responsavel: "",
  });

  useEffect(() => {
    if (bebida) {
      setForm({
        nome: bebida.nome || "",
        tipo: bebida.tipo_bebida || "alcoolica",
        volume: bebida.volume || "",
        responsavel: bebida.responsavel || "",
      });
    } else {
      setForm({
        nome: "",
        tipo: "alcoolica",
        volume: "",
        responsavel: "",
      });
    }
  }, [bebida]);

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!form.nome || !form.volume || !form.responsavel) {
      showToast("Preencha todos os campos obrigatórios", "danger");
      return;
    }

    try {
      if (bebida && bebida.id) {
        await api.post(`/bebida/${bebida.id}/update`, {
          nome: form.nome,
          tipo: form.tipo,
          volume: Number(form.volume),
          responsavel: form.responsavel,
        });
        showToast("Bebida atualizada com sucesso!", "success");
      } else {
        await api.post("/bebida", {
          nome: form.nome,
          tipo: form.tipo,
          volume: Number(form.volume),
          responsavel: form.responsavel,
        });
        showToast("Bebida salva com sucesso!", "success");
      }
      setForm({ nome: "", tipo: "alcoolica", volume: "", responsavel: "" });
      onSave();
    } catch (err) {
      const mensagemErro = err.response?.data?.error || "Erro desconhecido";
      showToast(mensagemErro, "danger");
    }
  };

  return (
    <Card className="shadow-sm border-0 mb-4">
      <Card.Body>
        <h4 className="mb-4">{bebida ? "Editar Bebida" : "Adicionar Bebida"}</h4>
        <Form onSubmit={handleSubmit}>
          <Row className="g-3">
            <Col lg={2}>
              <Form.Group>
                <Form.Label>Nome</Form.Label>
                <Form.Control
                  type="text"
                  placeholder="Ex: Cerveja"
                  value={form.nome}
                  onChange={(e) => setForm({ ...form, nome: e.target.value })}
                  required
                />
              </Form.Group>
            </Col>

            <Col lg={2}>
              <Form.Group>
                <Form.Label>Tipo</Form.Label>
                <Form.Select
                  value={form.tipo}
                  onChange={(e) => setForm({ ...form, tipo: e.target.value })}
                >
                  <option value="alcoolica">Alcoólica</option>
                  <option value="nao-alcoolica">Não Alcoólica</option>
                </Form.Select>
              </Form.Group>
            </Col>
            <Col lg={2}>
              <Form.Group>
                <Form.Label>Volume</Form.Label>
                <Form.Control
                  type="number"
                  placeholder="Ex: 12"
                  value={form.volume}
                  onChange={(e) => setForm({ ...form, volume: e.target.value })}
                  min="1"
                  required
                  readOnly={!!bebida?.id}
                />
              </Form.Group>
            </Col>
            <Col lg={2}>
              <Form.Group>
                <Form.Label>Responsável</Form.Label>
                <Form.Control
                  type="text"
                  placeholder="Ex: João"
                  value={form.responsavel}
                  onChange={(e) => setForm({ ...form, responsavel: e.target.value })}
                  required
                />
              </Form.Group>
            </Col>
          </Row>

          <div className="d-flex justify-content-end mt-4">
            <Button type="submit" variant="primary">
              {bebida ? "Editar" : "Salvar"}
            </Button>
          </div>
        </Form>
      </Card.Body>
    </Card>
  );
}
