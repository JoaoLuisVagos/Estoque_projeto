import { useEffect, useState } from "react";
import { Table, Button, Modal, Form, Col } from "react-bootstrap";
import api from "../../services/api";
import MovimentacaoList from "../movimentacoes/MovimentacaoList";
import MovimentacaoFormSave from "../movimentacoes/MovimentacaoFormSave";
import { FaEdit, FaTrash, FaPlus, FaList } from 'react-icons/fa';

export default function BebidaList({ showToast }) {
  const [bebidas, setBebidas] = useState([]);
  const [tipo, setTipo] = useState("alcoolica");
  const [selectedBebida, setSelectedBebida] = useState(null);

  const [showAddMovModalSave, setShowAddMovModalSave] = useState(false);
  const [showAddMovModalList, setShowAddMovModalList] = useState(false);
  const [movimentacoes, setMovimentacoes] = useState([]);

  const load = async () => {
    try {
      const res = await api.get("/bebidas");
      setBebidas(
        res.data.filter(b => b.tipo === tipo && String(b.excluido) === "0")
      );
    } catch (err) {
      showToast(err.response?.data?.error || "Erro ao carregar bebidas", "danger");
    }
  };

  const remove = async (id) => {
    try {
      await api.delete(`/bebida/${id}/delete`);
      showToast("Bebida excluída com sucesso!", "success");
      load();
    } catch (err) {
      showToast(err.response?.data?.error || "Erro ao excluir bebida", "danger");
    }
  };

  const handleSearchMovimentacoes = (data) => {
    setMovimentacoes(data);
  };

  useEffect(() => {
    load();
  }, [tipo]);

  return (
    <div>
      <h2 className="mb-3">📦 Estoque de Bebidas</h2>

      <Col lg={2} className="mb-3">
        <Form.Select className="mb-3" value={tipo} onChange={e => setTipo(e.target.value)}>
          <option value="alcoolica">Alcoólicas</option>
          <option value="nao-alcoolica">Não Alcoólicas</option>
        </Form.Select>
      </Col>

      <div className="table-responsive">
        <Table striped bordered hover>
          <thead>
            <tr>
              <th width="50px" className="text-center">#</th>
              <th>Nome</th>
              <th width="150px">Estoque Atual</th>
              <th width="400px">Responsável</th>
              <th width="150px" className="text-center">Ações</th>
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
                      variant="info"
                      size="sm"
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
            <MovimentacaoList bebidaId={selectedBebida.id} tipo={tipo} />
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
            />
          )}
        </Modal.Body>
      </Modal>

    </div>
  );
}
