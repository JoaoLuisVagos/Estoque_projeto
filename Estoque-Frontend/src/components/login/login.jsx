import React, { useState } from "react";
import { Form, Button, Card, Alert } from "react-bootstrap";
import { login } from "../../services/auth";
import Register from "./register";

export default function Login({ onLogin , onShowRegister}) {
  const [email, setEmail] = useState("");
  const [senha, setSenha] = useState("");
  const [erro, setErro] = useState("");
  const [showRegister, setShowRegister] = useState(false);
  const [successMsg, setSuccessMsg] = useState("");

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErro("");
    try {
      await login(email, senha);
      onLogin();
    } catch {
      setErro("E-mail ou senha inválidos");
    }
  };

  if (showRegister) {
    return (
      <Register
        onRegisterSuccess={(msg) => {
          setShowRegister(false);
          setSuccessMsg(msg);
        }}
        onVoltar={() => setShowRegister(false)}
      />
    );
  }

  return (
    <div className="d-flex justify-content-center align-items-center min-vh-100">
      <Card style={{ minWidth: 350 }}>
        <Card.Body>
          <h2 className="mb-4 text-center">Login</h2>
          {successMsg && <Alert variant="success">{successMsg}</Alert>}
          {erro && <Alert variant="danger">{erro}</Alert>}
          <Form onSubmit={handleSubmit}>
            <Form.Group className="mb-3">
              <Form.Label>E-mail</Form.Label>
              <Form.Control
                type="email"
                value={email}
                onChange={e => setEmail(e.target.value)}
                required
                autoFocus
              />
            </Form.Group>
            <Form.Group className="mb-3">
              <Form.Label>Senha</Form.Label>
              <Form.Control
                type="password"
                value={senha}
                onChange={e => setSenha(e.target.value)}
                required
              />
            </Form.Group>
            <Button type="submit" variant="primary" className="w-100 mb-2">
              Entrar
            </Button>
            <Button
              variant="link"
              onClick={onShowRegister}
              style={{ padding: 0, fontSize: "1em" }}
            >
              Cadastre-se
            </Button>
          </Form>
        </Card.Body>
      </Card>
    </div>
  );
}