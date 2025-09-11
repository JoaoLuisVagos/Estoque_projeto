import { useState, useEffect } from "react";
import { Container, Card, Tabs, Tab, Toast, ToastContainer, Row, Col } from "react-bootstrap";
import BebidaForm from "./components/bebidas/bebida-form";
import BebidaList from "./components/bebidas/bebida-list";
import MovimentacaoForm from "./components/movimentacoes/movimentacao-form";
import MovimentacaoList from "./components/movimentacoes/movimentacao-list";
import api from "./services/api";
import { FaBeer, FaChartBar, FaCoffee } from 'react-icons/fa';
import Login from "./components/login/login";
import { isAuthenticated, logout } from "./services/auth";

function App() {
  const [auth, setAuth] = useState(isAuthenticated());
  const [refresh, setRefresh] = useState(false);
  const [selectedBebida, setSelectedBebida] = useState(null);
  const [tabKey, setTabKey] = useState("bebidas");
  const [toast, setToast] = useState({ show: false, message: "", type: "success" });
  const [movimentacoesFiltradas, setMovimentacoesFiltradas] = useState(null);

  const [totais, setTotais] = useState({
    alcoolica: { total: 0, limite: 500, disponivel: 500 },
    "nao-alcoolica": { total: 0, limite: 400, disponivel: 400 },
  });

  const showToast = (message, type = "success") => {
    setToast({ show: false, message: "", type });
    setTimeout(() => {
      setToast({ show: true, message, type });
      setTimeout(() => setToast((prev) => ({ ...prev, show: false })), 3000);
    }, 100);
  };

  const loadTotais = async () => {
    try {
      const resAlcoolica = await api.get("/bebida/total/alcoolica");
      const resNaoAlcoolica = await api.get("/bebida/total/nao-alcoolica");
      setTotais({
        alcoolica: resAlcoolica.data,
        "nao-alcoolica": resNaoAlcoolica.data,
      });
    } catch (err) {
      console.error("Erro ao carregar totais:", err);
    }
  };

  useEffect(() => {
    if (auth) loadTotais();
  }, [auth, refresh]);

  if (!auth) return <Login onLogin={() => setAuth(true)} />;

  return (
    <>
      <Container fluid className="min-vh-100 d-flex flex-column py-4">
        <Row className="mb-4">
          <Col>
            <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
              <h1 className="text-center"><FaChartBar /> Gestão de Estoque</h1>
              <button className="btn btn-danger" onClick={() => { logout(); setAuth(false); }}>Logout</button>
            </div>
          </Col>
        </Row>
        <Row className="mb-4">
          <Col lg={6} className="mb-3 mb-lg-0">
            <Card className="shadow-sm border-0 text-center">
              <Card.Body>
                <h5><FaBeer /> Alcoólicas</h5>
                <p>Total: {totais.alcoolica.total} / {totais.alcoolica.limite}</p>
                <p>Disponível: {totais.alcoolica.disponivel}</p>
              </Card.Body>
            </Card>
          </Col>
          <Col lg={6}>
            <Card className="shadow-sm border-0 text-center">
              <Card.Body>
                <h5><FaCoffee /> Não Alcoólicas</h5>
                <p>Total: {totais["nao-alcoolica"].total} / {totais["nao-alcoolica"].limite}</p>
                <p>Disponível: {totais["nao-alcoolica"].disponivel}</p>
              </Card.Body>
            </Card>
          </Col>
        </Row>

        <Tabs activeKey={tabKey} onSelect={(k) => setTabKey(k)} className="mb-4">
          <Tab eventKey="bebidas" title="Bebidas">
            <Row className="g-4">
              <Col lg={12}>
                <Card className="shadow-sm border-0">
                  <Card.Body>
                    <BebidaForm
                      bebida={selectedBebida}
                      onSave={() => {
                        setRefresh(!refresh);
                        setSelectedBebida(null);
                      }}
                      showToast={showToast}
                    />
                  </Card.Body>
                </Card>
              </Col>
              <Col lg={12}>
                <BebidaList onEdit={setSelectedBebida}
                  showToast={showToast}
                  setRefresh={setRefresh}
                  refresh={refresh}
                />
              </Col>
            </Row>
          </Tab>

          <Tab eventKey="movimentacoes" title="Movimentações">
            <Row className="g-4">
              <Col lg={12}>
                <Card className="shadow-sm border-0">
                  <Card.Body>
                    <MovimentacaoForm
                      onSearch={(data) => setMovimentacoesFiltradas(Array.isArray(data) ? data : data ? [data] : [])}
                      showToast={showToast}
                    />
                  </Card.Body>
                </Card>
              </Col>
              <Col lg={12}>
                <MovimentacaoList
                  bebidaId={selectedBebida?.id}
                  movimentacoes={movimentacoesFiltradas}
                  key={refresh}
                />
              </Col>
            </Row>
          </Tab>
        </Tabs>

        <ToastContainer position="top-end" className="p-3">
          <Toast
            show={toast.show}
            bg={toast.type}
            onClose={() => setToast((prev) => ({ ...prev, show: false }))}
          >
            <Toast.Body className="text-white">{toast.message}</Toast.Body>
          </Toast>
        </ToastContainer>
      </Container>
    </>
  );
}

export default App;