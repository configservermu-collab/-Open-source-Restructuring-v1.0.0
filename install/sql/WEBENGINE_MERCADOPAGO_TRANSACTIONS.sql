SET ANSI_NULLS ON
SET QUOTED_IDENTIFIER ON
SET ANSI_PADDING ON

CREATE TABLE [dbo].[WEBENGINE_MERCADOPAGO_TRANSACTIONS](
    [id] [int] IDENTITY(1,1) NOT NULL,
    [ip_payed] [varchar](50) NULL,
    [userID] [varchar](25) NULL,
    [user_mercadoPago] [varchar](50) NULL,
    [id_compra] [varchar](25) NULL,
    [userMu] [varchar](50) NULL,
    [Credits] [varchar](50) NULL,
    [descript] [varchar](50) NULL,
    [card] [varchar](50) NULL,
    [card_method] [varchar](50) NULL,
    [card_name] [varchar](50) NULL,
    [card_dni] [varchar](50) NULL,
    [card_date_create] [varchar](50) NULL,
    [card_date_last_update] [varchar](50) NULL,
    [card_mount] [varchar](20) NULL,
    [type_coin_payed] [varchar](10) NULL,
    [state_compra] [varchar](50) NULL,
    [detail_compra] [varchar](50) NULL,
    [dato_aprobado] [varchar](50) NULL,
 PRIMARY KEY CLUSTERED
(
    [id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

SET ANSI_PADDING OFF


