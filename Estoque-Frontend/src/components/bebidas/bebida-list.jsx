import { useEffect, useState } from "react";
import React from 'react';
import { Table, Button, Modal, Form, Col } from "react-bootstrap";
import api from "../../services/api";
import MovimentacaoList from "../movimentacoes/movimentacao-list";
import MovimentacaoFormSave from "../movimentacoes/movimentacao-form-save";
import { FaEdit, FaTrash, FaPlus, FaList, FaBox } from 'react-icons/fa';
import BebidaForm from "./bebida-form";

export default function BebidaList({ showToast , onEdit, setRefresh, refresh }) {
  const [bebidas, setBebidas] = useState([]);
  const [tipo_bebida, setTipo_bebida] = useState("alcoolica");
  const [selectedBebida, setSelectedBebida] = useState(null);

  const [showAddMovModalSave, setShowAddMovModalSave] = useState(false);
  const [showAddMovModalList, setShowAddMovModalList] = useState(false);
  const [movimentacoes, setMovimentacoes] = useState([]);

  const load = async () => {
    try {
      const res = await api.get("/bebidas");
      setBebidas(
        res.data.filter(b => b.tipo_bebida === tipo_bebida && String(b.excluido) === "0")
      );
    } catch (err) {
      showToast(err.response?.data?.error || "Erro ao carregar bebidas", "danger");
    }
  };

  const remove = async (id) => {
    try {
      await api.delete(`/bebida/${id}/delete`);
      showToast("Bebida excluída com sucesso!", "success");
      if (selectedBebida?.id === id) setSelectedBebida(null);
      load();
      setRefresh && setRefresh(r => !r);
    } catch (err) {
      showToast(err.response?.data?.error || "Erro ao excluir bebida", "danger");
    }
  };

  const handleSearchMovimentacoes = (data) => {
    setMovimentacoes(data);
  };

  useEffect(() => {
    load();
  }, [tipo_bebida, refresh]);

  return (
    <div>
      <h2 className="mb-3"><FaBox /> Estoque de Bebidas</h2>

      <Col lg={2} className="mb-3">
        <Form.Select
          value={tipo_bebida}
          onChange={e => setTipo_bebida(e.target.value)}
        >
          <option value="alcoolica">Alcoólicas</option>
          <option value="nao-alcoolica">Não Alcoólicas</option>
        </Form.Select>
      </Col>

      <div className="table-responsive mt-4">
        <Table striped bordered hover>
          <thead>
            <tr>
              <th width="50px" className="text-center">#</th>
              <th>Nome</th>
              <th width="150px">Estoque Atual</th>
              <th width="400px">Responsável</th>
              <th width="200px" className="text-center">Ações</th>
            </tr>
          </thead>
          <tbody>
            {bebidas.length > 0 ? (
              bebidas.map(b => (
                <tr key={b.id}>
                  <td className="text-center">{b.id}</td>
                  <td>{b.nome}</td>
                  <td>{b.estoque_total} L</td>
                  <td>{b.responsavel}</td>
                  <td className="text-center">
                    <Button
                      variant="primary"
                      size="sm"
                      className="align-items-center justify-content-center"
                      title="Editar bebida"
                      onClick={() => onEdit(b)}
                    >
                      <FaEdit />
                    </Button>{" "}
                    <Button
                      variant="info"
                      size="sm"
                      className="align-items-center justify-content-center"
                      title="Adicionar movimentação"
                      onClick={() => {
                        setSelectedBebida(b);
                        setShowAddMovModalSave(true);
                      }}
                    >
                      <FaPlus />
                    </Button>{" "}
                    <Button
                      variant="warning"
                      size="sm"
                      className="align-items-center justify-content-center"
                      title="Ver movimentações"
                      onClick={() => {
                        setSelectedBebida(b);
                        setShowAddMovModalList(true);
                      }}
                    >
                      <FaList />
                    </Button>{" "}
                    <Button
                      variant="danger"
                      size="sm"
                      className="align-items-center justify-content-center"
                      title="Excluir bebida"
                      onClick={() => remove(b.id)}
                    >
                      <FaTrash />
                    </Button>
                  </td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="5" className="text-center text-muted">
                  Nenhuma bebida encontrada
                </td>
              </tr>
            )}
          </tbody>
        </Table>
      </div>

      <Modal show={showAddMovModalList} onHide={() => setShowAddMovModalList(false)} size="lg">
        <Modal.Header closeButton>
          <Modal.Title>Movimentações - {selectedBebida?.nome}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          {selectedBebida && (
            <MovimentacaoList bebidaId={selectedBebida.id} tipo_bebida={tipo_bebida} />
          )}
        </Modal.Body>
      </Modal>

      <Modal
        show={showAddMovModalSave}
        onHide={() => setShowAddMovModalSave(false)}
        size="lg"
      >
        <Modal.Header closeButton>
          <Modal.Title>Movimentações - {selectedBebida?.nome}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          {selectedBebida && (
            <MovimentacaoFormSave
              onSearch={handleSearchMovimentacoes}
              showToast={showToast}
              bebida={selectedBebida}
              onSave={() => {
                setShowAddMovModalSave(false);
                load();
                setRefresh && setRefresh(r => !r);
              }}
            />
          )}
        </Modal.Body>
      </Modal>
    </div>
  );
}
