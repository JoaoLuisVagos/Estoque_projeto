import { useState } from "react";
import api from "../../services/api";
import { Row, Col, Button, Form, Card } from "react-bootstrap";
import { useEffect } from "react";

export default function MovimentacaoFormSave({ onSave, showToast , bebida}) {
    const [form, setForm] = useState({
        bebida_id: bebida?.id || "",
        tipo: "entrada",
        volume: "",
        responsavel: "",
    });

    useEffect(() => {
        if (bebida) {
        setForm(prev => ({ ...prev, bebida_id: bebida.id }));
        }
    }, [bebida]);

    const handleSubmit = async (e) => {
        e.preventDefault();

        if (!form.bebida_id || !form.volume || !form.responsavel) {
            showToast("Preencha todos os campos obrigatórios", "danger");
            return;
        }

        try {
            await api.post("/movimentacao", {
                bebida_id: Number(form.bebida_id),
                tipo: form.tipo,
                volume: Number(form.volume),
                responsavel: form.responsavel,
            });

            showToast("Movimentação salva com sucesso!", "success");

            setForm({ bebida_id: "", tipo: "entrada", volume: "", responsavel: "" });
            onSave();
        } catch (err) {
            const mensagemErro = err.response?.data?.error || "Erro desconhecido";
            showToast(mensagemErro, "danger");
        }
    };

    return (
        <Card className="shadow-sm border-0 mb-4">
        <Card.Body>
            <h4 className="mb-4">➕ Adicionar Movimentação</h4>
            <Form onSubmit={handleSubmit}>
                <Row className="g-3">
                    <Col lg={3} md={6}>
                        <Form.Group>
                            <Form.Label>Id Bebida</Form.Label>
                            <Form.Control
                            type="number"
                            value={form.bebida_id}
                            readOnly
                            />
                        </Form.Group>
                    </Col>

                    <Col lg={3} md={6}>
                        <Form.Group>
                            <Form.Label>Tipo</Form.Label>
                            <Form.Select
                                value={form.tipo}
                                onChange={(e) =>
                                    setForm({ ...form, tipo: e.target.value })
                                }
                                >
                                <option value="entrada">Entrada</option>
                                <option value="saida">Saída</option>
                            </Form.Select>
                        </Form.Group>
                    </Col>

                    <Col lg={3} md={6}>
                        <Form.Group>
                            <Form.Label>Volume</Form.Label>
                            <Form.Control
                                type="number"
                                placeholder="Ex: 10"
                                value={form.volume}
                                onChange={(e) =>
                                    setForm({ ...form, volume: e.target.value })
                                }
                                min="1"
                                required
                            />
                        </Form.Group>
                    </Col>

                    <Col lg={3} md={6}>
                        <Form.Group>
                            <Form.Label>Responsável</Form.Label>
                            <Form.Control
                                type="text"
                                placeholder="Ex: João"
                                value={form.responsavel}
                                onChange={(e) =>
                                    setForm({ ...form, responsavel: e.target.value })
                                }
                                required
                            />
                        </Form.Group>
                    </Col>
                </Row>

                <div className="d-flex justify-content-end mt-4">
                    <Button type="submit" variant="success">
                    💾 Salvar
                    </Button>
                </div>
            </Form>
        </Card.Body>
        </Card>
    );
}
