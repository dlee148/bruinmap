-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 21, 2017 at 04:49 AM
-- Server version: 5.6.35
-- PHP Version: 7.1.6

--
-- Create Database
--

CREATE DATABASE bruinmap;
USE bruinmap;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Add new user to DB
--

CREATE USER 'bruinmap_user'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, UPDATE, DELETE ON bruinmap.* TO 'bruinmap_user'@'localhost';

--
-- Database: `bruinmap`
--

-- --------------------------------------------------------

--
-- Table structure for table `position_data`
--

CREATE TABLE `position_data` (
  `id` int(11) NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `time_stamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `position_data`
--
ALTER TABLE `position_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `position_data`
--
ALTER TABLE `position_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
