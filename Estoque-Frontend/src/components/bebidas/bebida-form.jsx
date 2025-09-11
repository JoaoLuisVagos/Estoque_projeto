import { useState, useEffect, useRef } from "react";
import React from 'react';
import api from "../../services/api";
import { Row, Col, Button, Form, Card } from "react-bootstrap";

export default function BebidaForm({ onSave, showToast, bebida }) {
  const [form, setForm] = useState({
    nome: "",
    tipo_bebida: "alcoolica",
    estoque_total: "",
    responsavel: "",
    imagem: "",
  });
  const [imagemPreview, setImagemPreview] = useState(bebida?.imagem ? `${import.meta.env.VITE_API_URL}/imagens/${bebida.imagem}` : null);
  const fileInputRef = useRef();

  useEffect(() => {
    if (bebida) {
      setForm({
        nome: bebida.nome || "",
        tipo_bebida: bebida.tipo_bebida || "alcoolica",
        estoque_total: bebida.estoque_total || "",
        responsavel: bebida.responsavel || "",
        imagem: bebida.imagem || "",
      });
      setImagemPreview(bebida.imagem ? `${import.meta.env.VITE_API_URL}/imagens/${bebida.imagem}` : null);
    } else {
      setForm({
        nome: "",
        tipo_bebida: "alcoolica",
        estoque_total: "",
        responsavel: "",
        imagem: "",
      });
      setImagemPreview(null);
    }
  }, [bebida]);

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setImagemPreview(URL.createObjectURL(file));
      setForm({ ...form, imagemFile: file });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!form.nome || !form.estoque_total || !form.responsavel) {
      showToast("Preencha todos os campos obrigatórios", "danger");
      return;
    }

    try {
      const data = new FormData();
      data.append("nome", form.nome);
      data.append("tipo_bebida", form.tipo_bebida);
      data.append("estoque_total", form.estoque_total);
      data.append("responsavel", form.responsavel);
      if (form.imagemFile) {
        data.append("imagem", form.imagemFile);
      }

      if (bebida && bebida.id) {
        await api.post(`/bebida/${bebida.id}/update`, data, {
          headers: { "Content-Type": "multipart/form-data" },
        });
        showToast("Bebida atualizada com sucesso!", "success");
      } else {
        await api.post("/bebida", data, {
          headers: { "Content-Type": "multipart/form-data" },
        });
        showToast("Bebida salva com sucesso!", "success");
      }

      setForm({ nome: "", tipo_bebida: "alcoolica", estoque_total: "", responsavel: "", imagem: "" });
      setImagemPreview(null);
      onSave();
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
        <h4 className="mb-4">{bebida ? "Editar Bebida" : "Adicionar Bebida"}</h4>
        <Form onSubmit={handleSubmit}>
          <Row className="g-3">
            <Col lg={2} className="d-flex align-items-center">
              <Form.Group className="w-100 text-center">
                <Form.Label>Imagem</Form.Label>
                <div className="bebida-img-preview-box">
                  {imagemPreview ? (
                    <img src={imagemPreview} alt="Imagem da bebida" />
                  ) : (
                    <span style={{ color: "#bbb" }}>Prévia</span>
                  )}
                  <Form.Control
                    type="file"
                    accept="image/*"
                    onChange={handleFileChange}
                    className="bebida-img-file-input"
                    title="Escolher imagem"
                  />
                </div>
              </Form.Group>
            </Col>
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
                  value={form.tipo_bebida}
                  onChange={(e) => setForm({ ...form, tipo_bebida: e.target.value })}
                >
                  <option value="alcoolica">Alcoólica</option>
                  <option value="nao-alcoolica">Não Alcoólica</option>
                </Form.Select>
              </Form.Group>
            </Col>
            <Col lg={2}>
              <Form.Group className="mb-3">
                <Form.Label>Estoque Total (litros)</Form.Label>
                <Form.Control
                  type="number"
                  step="0.01"
                  min="0"
                  value={form.estoque_total === undefined || form.estoque_total === null ? "" : form.estoque_total}
                  onChange={e => setForm({ ...form, estoque_total: e.target.value })}
                  placeholder="Informe o estoque em litros"
                  required
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
            <Button variant="secondary" className="me-2" onClick={() => {
              setForm({ nome: "", tipo_bebida: "alcoolica", estoque_total: "", responsavel: "", imagem: "" });
              setImagemPreview(null);
              if (fileInputRef.current) {
                fileInputRef.current.value = null;
              }
              onSave();
            }}>
              Cancelar
            </Button>
            <Button type="submit" variant="primary">
              {bebida ? "Editar" : "Salvar"}
            </Button>
          </div>
        </Form>
      </Card.Body>
    </Card>
  );
}