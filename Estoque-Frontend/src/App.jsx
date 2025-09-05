import { useState, useEffect } from "react";
import { Container, Card, Tabs, Tab, Toast, ToastContainer, Row, Col } from "react-bootstrap";
import BebidaForm from "./components/bebidas/BebidaForm";
import BebidaList from "./components/bebidas/BebidaList";
import MovimentacaoForm from "./components/movimentacoes/MovimentacaoForm";
import MovimentacaoList from "./components/movimentacoes/MovimentacaoList";
import api from "./services/api";

function App() {
  const [refresh, setRefresh] = useState(false);
  const [selectedBebida, setSelectedBebida] = useState(null);
  const [tabKey, setTabKey] = useState("bebidas");
  const [toast, setToast] = useState({ show: false, message: "", type: "success" });

  const [totais, setTotais] = useState({
    alcoolica: { total: 0, limite: 500, disponivel: 500 },
    "nao-alcoolica": { total: 0, limite: 400, disponivel: 400 },
  });

  const showToast = (message, type = "success") => {
    setToast({ show: true, message, type });
    setTimeout(() => setToast((prev) => ({ ...prev, show: false })), 3000);
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
    loadTotais();
  }, [refresh]);

  return (
    <Container
      fluid
      className="min-vh-100 d-flex justify-content-center align-items-center"
    >
      <Row className="w-100 justify-content-center">
        <Col lg={12}>
          <h1 className="mb-4 text-center">📊 Gestão de Estoque</h1>

          <Row className="mb-4 justify-content-center">
            <Col lg={6} className="mb-3 mb-md-0">
              <Card className="shadow-sm border-0 text-center">
                <Card.Body>
                  <h5>🍺 Alcoólicas</h5>
                  <p>
                    Total: {totais.alcoolica.total} / {totais.alcoolica.limite}
                  </p>
                  <p>Disponível: {totais.alcoolica.disponivel}</p>
                </Card.Body>
              </Card>
            </Col>
            <Col lg={6}>
              <Card className="shadow-sm border-0 text-center">
                <Card.Body>
                  <h5>🥤 Não Alcoólicas</h5>
                  <p>
                    Total: {totais["nao-alcoolica"].total} / {totais["nao-alcoolica"].limite}
                  </p>
                  <p>Disponível: {totais["nao-alcoolica"].disponivel}</p>
                </Card.Body>
              </Card>
            </Col>
          </Row>

          <Tabs
            activeKey={tabKey}
            onSelect={(k) => setTabKey(k)}
            className="mb-4 justify-content-center"
          >
            <Tab eventKey="bebidas" title="Bebidas">
              <Row>
                <Col lg={12}>
                  <Card className="shadow-sm border-0 mb-4">
                    <Card.Body>
                      <BebidaForm
                        onSave={() => setRefresh(!refresh)}
                        showToast={showToast}
                      />
                    </Card.Body>
                  </Card>
                </Col>
                <Col lg={12}>
                  <BebidaList onEdit={setSelectedBebida} key={refresh} />
                </Col>
              </Row>
            </Tab>

            <Tab eventKey="movimentacoes" title="Movimentações">
              <Row>
                <Col lg={12}>
                  <Card className="shadow-sm border-0 mb-4">
                    <Card.Body>
                      <MovimentacaoForm
                        onSave={() => setRefresh(!refresh)}
                        showToast={showToast}
                      />
                    </Card.Body>
                  </Card>
                </Col>
                <Col lg={12}>
                  <MovimentacaoList bebidaId={selectedBebida?.id} key={refresh} />
                </Col>
              </Row>
            </Tab>
          </Tabs>
        </Col>
      </Row>

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
  );
}

export default App;
