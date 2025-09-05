import { useEffect, useState } from "react";
import { Table, Button } from "react-bootstrap";
import api from "../../services/api";

export default function MovimentacaoList({ bebidaId }) {
  const [movs, setMovs] = useState([]);

  const load = async () => {
    let url = bebidaId
      ? `/movimentacao/bebida/${bebidaId}/getByID?excluido=0`
      : "/movimentacoes?excluido=0";

    try {
      const res = await api.get(url);

      const data = Array.isArray(res.data)
        ? res.data
        : res.data
        ? [res.data]
        : [];

      setMovs(data);
    } catch (err) {
      console.error("Erro ao carregar movimentações:", err);
      setMovs([]);
    }
  };

  useEffect(() => {
    load();
  }, [bebidaId]);

  return (
    <div>
      <h2 className="mb-3">📦 Movimentações</h2>
      <div className="table-responsive">
        <Table striped bordered hover>
          <thead>
            <tr>
              <th className="text-center">#</th>
              <th className="text-center">Tipo</th>
              <th className="text-center">ID Bebida</th>
              <th width="20%" className="text-center">Bebida</th>
              <th>Unidades</th>
              <th>Responsável</th>
              <th>Data</th>
            </tr>
          </thead>
          <tbody>
            {Array.isArray(movs) && movs.length > 0 ? (
              movs.map((m) => (
                <tr key={m.id} className={m.tipo === "saida" ? "table-danger" : "table-success"}>
                  <td className="text-center">{m.id}</td>
                  <td className="text-center">{m.tipo === "saida" ? "Saida" : "Entrada"}</td>
                  <td className="text-center">{m.bebida_id}</td>
                  <td >{m.bebida}</td>
                  <td>{m.volume} L</td>
                  <td>{m.responsavel}</td>
                  <td>{m.data_registro}</td>
                </tr>
              ))
            ) : (
              <tr>
                <td colSpan="7" className="text-center text-muted">
                  Nenhuma movimentação encontrada
                </td>
              </tr>
            )}
          </tbody>
        </Table>
      </div>
    </div>
  );
}
