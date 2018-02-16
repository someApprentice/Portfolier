--
-- PostgreSQL database dump
--

-- Dumped from database version 10.1
-- Dumped by pg_dump version 10.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: migration_versions; Type: TABLE; Schema: public; Owner: portfolier
--

CREATE TABLE migration_versions (
    version character varying(255) NOT NULL
);


ALTER TABLE migration_versions OWNER TO portfolier;

--
-- Name: portfolio; Type: TABLE; Schema: public; Owner: portfolier
--

CREATE TABLE portfolio (
    id integer NOT NULL,
    user_id integer NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE portfolio OWNER TO portfolier;

--
-- Name: portfolio_id_seq; Type: SEQUENCE; Schema: public; Owner: portfolier
--

CREATE SEQUENCE portfolio_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE portfolio_id_seq OWNER TO portfolier;

--
-- Name: stock; Type: TABLE; Schema: public; Owner: portfolier
--

CREATE TABLE stock (
    id integer NOT NULL,
    portfolio_id integer NOT NULL,
    symbol character varying(255) NOT NULL,
    date timestamp(0) with time zone NOT NULL
);


ALTER TABLE stock OWNER TO portfolier;

--
-- Name: stock_id_seq; Type: SEQUENCE; Schema: public; Owner: portfolier
--

CREATE SEQUENCE stock_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE stock_id_seq OWNER TO portfolier;

--
-- Name: user; Type: TABLE; Schema: public; Owner: portfolier
--

CREATE TABLE "user" (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    hash character varying(255) NOT NULL
);


ALTER TABLE "user" OWNER TO portfolier;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: portfolier
--

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE user_id_seq OWNER TO portfolier;

--
-- Name: migration_versions migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: portfolier
--

ALTER TABLE ONLY migration_versions
    ADD CONSTRAINT migration_versions_pkey PRIMARY KEY (version);


--
-- Name: portfolio portfolio_pkey; Type: CONSTRAINT; Schema: public; Owner: portfolier
--

ALTER TABLE ONLY portfolio
    ADD CONSTRAINT portfolio_pkey PRIMARY KEY (id);


--
-- Name: stock stock_pkey; Type: CONSTRAINT; Schema: public; Owner: portfolier
--

ALTER TABLE ONLY stock
    ADD CONSTRAINT stock_pkey PRIMARY KEY (id);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: portfolier
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: idx_4b365660b96b5643; Type: INDEX; Schema: public; Owner: portfolier
--

CREATE INDEX idx_4b365660b96b5643 ON stock USING btree (portfolio_id);


--
-- Name: idx_a9ed1062a76ed395; Type: INDEX; Schema: public; Owner: portfolier
--

CREATE INDEX idx_a9ed1062a76ed395 ON portfolio USING btree (user_id);


--
-- Name: uniq_8d93d6495e237e06; Type: INDEX; Schema: public; Owner: portfolier
--

CREATE UNIQUE INDEX uniq_8d93d6495e237e06 ON "user" USING btree (name);


--
-- Name: uniq_8d93d649e7927c74; Type: INDEX; Schema: public; Owner: portfolier
--

CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" USING btree (email);


--
-- Name: stock fk_4b365660b96b5643; Type: FK CONSTRAINT; Schema: public; Owner: portfolier
--

ALTER TABLE ONLY stock
    ADD CONSTRAINT fk_4b365660b96b5643 FOREIGN KEY (portfolio_id) REFERENCES portfolio(id) ON DELETE CASCADE;


--
-- Name: portfolio fk_a9ed1062a76ed395; Type: FK CONSTRAINT; Schema: public; Owner: portfolier
--

ALTER TABLE ONLY portfolio
    ADD CONSTRAINT fk_a9ed1062a76ed395 FOREIGN KEY (user_id) REFERENCES "user"(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

