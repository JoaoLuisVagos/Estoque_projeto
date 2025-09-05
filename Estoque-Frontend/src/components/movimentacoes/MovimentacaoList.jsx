import { useEffect, useState } from "react";
import { Table, Button } from "react-bootstrap";
import api from "../../services/api";
import { FaBox } from "react-icons/fa";

export default function MovimentacaoList({ bebidaId, movimentacoes }) {
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

  function formatarDataBR(dataISO) {
    if (!dataISO) return "";
    const data = new Date(dataISO);
    const dia = String(data.getDate()).padStart(2, "0");
    const mes = String(data.getMonth() + 1).padStart(2, "0");
    const ano = data.getFullYear();
    const hora = String(data.getHours()).padStart(2, "0");
    const min = String(data.getMinutes()).padStart(2, "0");
    return `${dia}/${mes}/${ano} ${hora}:${min}`;
  }

  useEffect(() => {
    load();
  }, [bebidaId]);

  const lista = movimentacoes ?? movs;

  return (
    <div>
      <h2 className="mb-3"><FaBox /> Movimentações</h2>
      <div className="table-responsive">
        <Table striped bordered hover>
          <thead>
            <tr>
              <th className="text-center">#</th>
              <th className="text-center">Tipo</th>
              <th className="text-center">ID Bebida</th>
              <th width="20%" className="text-center">Bebida</th>
              <th>Volume</th>
              <th>Responsável</th>
              <th>Data</th>
            </tr>
          </thead>
          <tbody>
            {Array.isArray(lista) && lista.length > 0 ? (
              lista.map((m) => (
                <tr key={m.id} className={m.tipo === "saida" ? "table-danger" : "table-success"}>
                  <td className="text-center">{m.id}</td>
                  <td className="text-center">{m.tipo === "saida" ? "Saida" : "Entrada"}</td>
                  <td className="text-center">{m.bebida_id}</td>
                  <td >{m.bebida}</td>
                  <td>{m.volume} L</td>
                  <td>{m.responsavel}</td>
                  <td>{formatarDataBR(m.data_registro)}</td>
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
