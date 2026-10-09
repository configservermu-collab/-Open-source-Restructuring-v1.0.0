
/****** Object:  Table [dbo].[WEBENGINE_PAYPAL_TRANSACTIONS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_PAYPAL_TRANSACTIONS](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[transaction_id] [varchar](50) NOT NULL,
	[user_id] [int] NOT NULL,
	[payment_amount] [varchar](50) NOT NULL,
	[paypal_email] [varchar](200) NOT NULL,
	[transaction_date] [varchar](50) NOT NULL,
	[transaction_status] [int] NOT NULL,
	[order_id] [varchar](50) NOT NULL,
 CONSTRAINT [PK_WEBENGINE_PAYPAL_TRANSACTIONS] PRIMARY KEY CLUSTERED 
(
	[transaction_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF


