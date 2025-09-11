import { useState, useEffect } from "react";
import { Form, Button, Alert, Card } from "react-bootstrap";
import api from "../../services/api";

export default function Register({ onRegister }) {
  const [form, setForm] = useState({ nome: "", email: "", senha: "" });
  const [mensagem, setMensagem] = useState("");
  const [tipoMensagem, setTipoMensagem] = useState("success");

  useEffect(() => {
    if (mensagem) {
      const timer = setTimeout(() => setMensagem(""), 3000);
      return () => clearTimeout(timer);
    }
  }, [mensagem]);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
    if (mensagem) setMensagem("");
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post("/api/registrar", form);
      setTipoMensagem("success");
      setMensagem("Usuário criado com sucesso! Redirecionando para login...");
      setForm({ nome: "", email: "", senha: "" });
      setTimeout(() => {
        if (onRegister) onRegister();
      }, 1500);
    } catch (err) {
      let mensagemErro = err.response?.data || "Erro desconhecido";
      if (typeof mensagemErro === "object" && mensagemErro !== null) {
        mensagemErro = mensagemErro.erro || JSON.stringify(mensagemErro);
      }
      setTipoMensagem("danger");
      setMensagem(mensagemErro || "Erro ao criar usuário");
    }
  };

  return (
    <div className="d-flex justify-content-center align-items-center min-vh-100 bg-light">
      <Card style={{ minWidth: 350 }}>
        <Card.Body>
          <h3 className="mb-4 text-center">Cadastro</h3>
          {mensagem && <Alert variant={tipoMensagem}>{mensagem}</Alert>}
          <Form onSubmit={handleSubmit}>
            <Form.Group className="mb-3">
              <Form.Label>Nome</Form.Label>
              <Form.Control
                type="text"
                name="nome"
                value={form.nome}
                onChange={handleChange}
                required
              />
            </Form.Group>
            <Form.Group className="mb-3">
              <Form.Label>Email</Form.Label>
              <Form.Control
                type="email"
                name="email"
                value={form.email}
                onChange={handleChange}
                required
              />
            </Form.Group>
            <Form.Group className="mb-3">
              <Form.Label>Senha</Form.Label>
              <Form.Control
                type="password"
                name="senha"
                value={form.senha}
                onChange={handleChange}
                required
              />
            </Form.Group>
            <Button type="submit" variant="primary" className="w-100">
              Cadastrar
            </Button>
          </Form>
        </Card.Body>
      </Card>
    </div>
  );
}