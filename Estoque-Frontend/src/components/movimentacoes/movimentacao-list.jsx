import React from 'react';
import { useEffect, useState } from "react";
import api from "../../services/api";
import { Table, Form, Row, Col, Button } from "react-bootstrap";

export default function MovimentacaoList({ bebidaId, movimentacoes }) {
  const [movs, setMovs] = useState([]);
  const [sortField, setSortField] = useState("data_registro");
  const [sortOrder, setSortOrder] = useState("desc");

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

  const lista = movimentacoes && movimentacoes.length > 0 ? movimentacoes : movs;

  useEffect(() => {
    load();
  }, [bebidaId]);

  const sortedList = [...lista].sort((a, b) => {
    let aValue = a[sortField];
    let bValue = b[sortField];

    if (sortField === "data_registro") {
      aValue = new Date(aValue);
      bValue = new Date(bValue);
    }
    if (sortField === "volume" || sortField === "id") {
      aValue = Number(aValue);
      bValue = Number(bValue);
    }

    if (aValue < bValue) return sortOrder === "asc" ? -1 : 1;
    if (aValue > bValue) return sortOrder === "asc" ? 1 : -1;
    return 0;
  });

  const handleSort = (field) => {
    if (sortField === field) {
      setSortOrder(sortOrder === "asc" ? "desc" : "asc");
    } else {
      setSortField(field);
      setSortOrder("asc");
    }
  };

  const sortIcon = (field) =>
    sortField === field ? (sortOrder === "asc" ? " ▲" : " ▼") : "";

  const getTipoLabel = (tipo) => {
    if (tipo === "entrada") return "Entrada";
    if (tipo === "saida") return "Saída";
    if (tipo === "alcoolica") return "Álcoolica";
    if (tipo === "nao-alcoolica") return "Não Alcoólica";
    return tipo;
  };

  return (
    <div>
      <Table striped bordered hover>
        <thead>
          <tr>
            <th onClick={() => handleSort("id")} style={{ cursor: "pointer" }}>
              #
              {sortIcon("id")}
            </th>
            <th onClick={() => handleSort("tipo")} style={{ cursor: "pointer" }}>
              Tipo
              {sortIcon("tipo")}
            </th>
            <th onClick={() => handleSort("bebida_id")} style={{ cursor: "pointer" }} width="5%">
              Id Bebida
              {sortIcon("bebida_id")}
            </th>
            <th onClick={() => handleSort("bebida")} style={{ cursor: "pointer" }}>
              Bebida
              {sortIcon("bebida")}
            </th>
            <th onClick={() => handleSort("tipo_bebida")} style={{ cursor: "pointer" }}>
              Tipo Bebida
              {sortIcon("tipo_bebida")}
            </th>
            <th onClick={() => handleSort("volume")} style={{ cursor: "pointer" }}>
              Volume
              {sortIcon("volume")}
            </th>
            <th onClick={() => handleSort("responsavel")} style={{ cursor: "pointer" }}>
              Responsável
              {sortIcon("responsavel")}
            </th>
            <th onClick={() => handleSort("data_registro")} style={{ cursor: "pointer" }}>
              Data
              {sortIcon("data_registro")}
            </th>
          </tr>
        </thead>
        <tbody>
          {sortedList.length > 0 ? (
            sortedList.map((m) => (
              <tr
                key={m.id}
                className={
                  m.tipo === "saida"
                    ? "table-danger"
                    : m.tipo === "entrada"
                    ? "table-success"
                    : ""
                }
              >
                <td>{m.id}</td>
                <td>{getTipoLabel(m.tipo)}</td>
                <td>{m.bebida_id}</td>
                <td>{m.bebida}</td>
                <td>{getTipoLabel(m.tipo_bebida)}</td>
                <td>{m.volume}</td>
                <td>{m.responsavel}</td>
                <td>{new Date(m.data_registro).toLocaleString("pt-BR")}</td>
              </tr>
            ))
          ) : (
            <tr>
              <td colSpan={5} className="text-center text-muted">
                Nenhuma movimentação encontrada
              </td>
            </tr>
          )}
        </tbody>
      </Table>
    </div>
  );
}
