--
-- PostgreSQL database dump
--

-- Dumped from database version 16.6 (Debian 16.6-1.pgdg120+1)
-- Dumped by pg_dump version 16.6 (Ubuntu 16.6-0ubuntu0.24.04.1)

-- Started on 2024-12-24 10:09:49 -03

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA public;


--
-- TOC entry 3398 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 217 (class 1259 OID 25060)
-- Name: acessorios; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.acessorios (
    id integer NOT NULL,
    descricao character varying
);


--
-- TOC entry 218 (class 1259 OID 25067)
-- Name: clientes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clientes (
    id integer NOT NULL,
    nome character varying(255) NOT NULL,
    dt_nascimento character varying(20),
    documento character varying(50),
    tipo_pessoa character varying(20),
    endereco character varying(120),
    bairro character varying(80),
    numero character varying(80),
    complemento character varying(120),
    cep character varying(20),
    cidade character varying(80),
    fone character varying(30),
    email character varying(50)
);


--
-- TOC entry 215 (class 1259 OID 16389)
-- Name: fabricantes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fabricantes (
    id bigint NOT NULL,
    nome character varying(255),
    logo character varying(255)
);


--
-- TOC entry 216 (class 1259 OID 16862)
-- Name: people; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.people (
    id integer,
    name text
);


--
-- TOC entry 219 (class 1259 OID 25074)
-- Name: veiculos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.veiculos (
    id integer NOT NULL,
    descricao character varying(255),
    placa character varying(15),
    ano integer,
    cor character varying(20),
    km integer,
    valor numeric,
    obs character varying(500),
    fabricante_id integer
);


--
-- TOC entry 221 (class 1259 OID 25118)
-- Name: veiculos_acessorios; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.veiculos_acessorios (
    id integer NOT NULL,
    veiculos_id integer NOT NULL,
    acessorios_id integer NOT NULL
);


--
-- TOC entry 220 (class 1259 OID 25091)
-- Name: vendas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.vendas (
    id integer NOT NULL,
    dt_venda character varying(20),
    valor numeric,
    obs character varying(500),
    cliente_id integer,
    veiculo_id integer
);


--
-- TOC entry 3388 (class 0 OID 25060)
-- Dependencies: 217
-- Data for Name: acessorios; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.acessorios VALUES (1, 'acc1');
INSERT INTO public.acessorios VALUES (2, 'acc2');
INSERT INTO public.acessorios VALUES (3, 'acc3');
INSERT INTO public.acessorios VALUES (4, 'acc4');


--
-- TOC entry 3389 (class 0 OID 25067)
-- Dependencies: 218
-- Data for Name: clientes; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.clientes VALUES (1, '123', '213123', '12312', NULL, '123', '123', '123', '123', '123', '123', '123', '123');
INSERT INTO public.clientes VALUES (2, 'pessoa 1', '20/12/1980', '5555555555555555', 'Física', 'endereço 1', 'bairro 1', '1500', 'compl 1', '92080-550', 'Porto Alegre', '(59) 65412-1142', 'asdasd@asdasd.com');


--
-- TOC entry 3386 (class 0 OID 16389)
-- Dependencies: 215
-- Data for Name: fabricantes; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.fabricantes VALUES (1, 'fabricante 1', 'third-party-manufacturing-for-skin-care-products-500x500.jpg');
INSERT INTO public.fabricantes VALUES (2, 'fabricante 2', 'depositphotos_126693854-stock-photo-set-of-body-care-products.jpg');
INSERT INTO public.fabricantes VALUES (3, 'fabricante 3', 'composicao-de-cosmeticos-com-frascos-de-soro_23-2148549119.jpg');


--
-- TOC entry 3387 (class 0 OID 16862)
-- Dependencies: 216
-- Data for Name: people; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.people VALUES (1, 'Unit A - person 1');
INSERT INTO public.people VALUES (2, 'Unit A - person 2');


--
-- TOC entry 3390 (class 0 OID 25074)
-- Dependencies: 219
-- Data for Name: veiculos; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.veiculos VALUES (1, '1', '1111', NULL, 'Preto', 111, 1.11, '111', NULL);
INSERT INTO public.veiculos VALUES (2, '2333', '333', NULL, 'Prata', 111, 2.22, '333', NULL);
INSERT INTO public.veiculos VALUES (3, '123', '2133', NULL, 'Prata', 333, 3.33, '3333', 2);
INSERT INTO public.veiculos VALUES (4, '123123', '12312312', NULL, 'Cinza', 12312312, 3123123.12, '3123123213', 1);
INSERT INTO public.veiculos VALUES (6, 'veiculo 2', 'aaa-1234', 1950, 'Cinza', 123123, 123123.12, 'testetsadsadsad
as
das
d
asdasd
as
d', 2);
INSERT INTO public.veiculos VALUES (7, '1', '1', 1, 'Branco', 11, 0.11, NULL, 1);
INSERT INTO public.veiculos VALUES (5, 'veiculo 1', 'asds4444', 1980, 'Vermelho', 1111, 11.11, '123123123123', 2);
INSERT INTO public.veiculos VALUES (8, 'veiculo 3', 'asd1555', 2024, 'Preto', 1500, 190000.00, NULL, NULL);


--
-- TOC entry 3392 (class 0 OID 25118)
-- Dependencies: 221
-- Data for Name: veiculos_acessorios; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.veiculos_acessorios VALUES (2, 6, 2);
INSERT INTO public.veiculos_acessorios VALUES (3, 6, 4);
INSERT INTO public.veiculos_acessorios VALUES (4, 6, 3);
INSERT INTO public.veiculos_acessorios VALUES (8, 7, 1);
INSERT INTO public.veiculos_acessorios VALUES (9, 7, 2);
INSERT INTO public.veiculos_acessorios VALUES (10, 5, 1);
INSERT INTO public.veiculos_acessorios VALUES (11, 5, 4);


--
-- TOC entry 3391 (class 0 OID 25091)
-- Dependencies: 220
-- Data for Name: vendas; Type: TABLE DATA; Schema: public; Owner: -
--

INSERT INTO public.vendas VALUES (2, '24/12/2024', 58000.00, 'venda 2', 1, 6);
INSERT INTO public.vendas VALUES (1, '24/12/2021', 80000.00, 'venda 1', 2, 7);


--
-- TOC entry 3229 (class 2606 OID 25066)
-- Name: acessorios acessorios_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.acessorios
    ADD CONSTRAINT acessorios_pk PRIMARY KEY (id);


--
-- TOC entry 3231 (class 2606 OID 25073)
-- Name: clientes cliente_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clientes
    ADD CONSTRAINT cliente_pk PRIMARY KEY (id);


--
-- TOC entry 3227 (class 2606 OID 17345)
-- Name: fabricantes fabricantes_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fabricantes
    ADD CONSTRAINT fabricantes_pk PRIMARY KEY (id);


--
-- TOC entry 3233 (class 2606 OID 25080)
-- Name: veiculos veiculo_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.veiculos
    ADD CONSTRAINT veiculo_pk PRIMARY KEY (id);


--
-- TOC entry 3237 (class 2606 OID 25122)
-- Name: veiculos_acessorios veiculos_acessorios_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.veiculos_acessorios
    ADD CONSTRAINT veiculos_acessorios_pk PRIMARY KEY (id);


--
-- TOC entry 3235 (class 2606 OID 25097)
-- Name: vendas venda_pk; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vendas
    ADD CONSTRAINT venda_pk PRIMARY KEY (id);


--
-- TOC entry 3241 (class 2606 OID 33288)
-- Name: veiculos_acessorios veiculos_acessorios_acessorios_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.veiculos_acessorios
    ADD CONSTRAINT veiculos_acessorios_acessorios_fk FOREIGN KEY (acessorios_id) REFERENCES public.acessorios(id);


--
-- TOC entry 3242 (class 2606 OID 33293)
-- Name: veiculos_acessorios veiculos_acessorios_veiculos_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.veiculos_acessorios
    ADD CONSTRAINT veiculos_acessorios_veiculos_fk FOREIGN KEY (veiculos_id) REFERENCES public.veiculos(id);


--
-- TOC entry 3238 (class 2606 OID 33283)
-- Name: veiculos veiculos_fabricantes_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.veiculos
    ADD CONSTRAINT veiculos_fabricantes_fk FOREIGN KEY (fabricante_id) REFERENCES public.fabricantes(id);


--
-- TOC entry 3239 (class 2606 OID 33298)
-- Name: vendas vendas_clientes_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vendas
    ADD CONSTRAINT vendas_clientes_fk FOREIGN KEY (cliente_id) REFERENCES public.clientes(id);


--
-- TOC entry 3240 (class 2606 OID 33303)
-- Name: vendas vendas_veiculos_fk; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.vendas
    ADD CONSTRAINT vendas_veiculos_fk FOREIGN KEY (veiculo_id) REFERENCES public.veiculos(id);


-- Completed on 2024-12-24 10:09:49 -03

--
-- PostgreSQL database dump complete
--

