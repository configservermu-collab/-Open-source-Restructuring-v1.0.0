
/****** Object:  Table [dbo].[WEBENGINE_CREDITS_LOGS]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS OFF

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING OFF

CREATE TABLE [dbo].[WEBENGINE_CREDITS_LOGS](
	[log_id] [int] IDENTITY(1,1) NOT NULL,
	[log_config] [varchar](50) NOT NULL,
	[log_identifier] [varchar](50) NOT NULL,
	[log_credits] [int] NOT NULL,
	[log_transaction] [varchar](50) NOT NULL,
	[log_date] [varchar](50) NOT NULL,
	[log_inadmincp] [tinyint] NULL,
	[log_module] [varchar](50) NULL,
	[log_ip] [varchar](50) NULL,
 CONSTRAINT [PK_WEBENGINE_CREDITS_LOGS] PRIMARY KEY CLUSTERED 
(
	[log_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

SET IDENTITY_INSERT [dbo].[WEBENGINE_CREDITS_LOGS] ON 

INSERT [dbo].[WEBENGINE_CREDITS_LOGS] ([log_id], [log_config], [log_identifier], [log_credits], [log_transaction], [log_date], [log_inadmincp], [log_module], [log_ip]) VALUES (1, N'WCoinC', N'Config', 100, N'add', N'1750786749', 1, N'Paypal', N'109.60.97.85')
SET IDENTITY_INSERT [dbo].[WEBENGINE_CREDITS_LOGS] OFF


