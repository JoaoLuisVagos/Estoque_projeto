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

  const lista = movimentacoes ?? movs;

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
              <tr key={m.id}>
                <td>{m.id}</td>
                <td>{m.tipo}</td>
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
