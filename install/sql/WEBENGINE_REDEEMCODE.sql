
/****** Object:  Table [dbo].[WEBENGINE_REDEEMCODE]    Script Date: 29/07/2025 19:39:15 ******/
SET ANSI_NULLS ON

SET QUOTED_IDENTIFIER ON

SET ANSI_PADDING ON

CREATE TABLE [dbo].[WEBENGINE_REDEEMCODE](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[redeem_code] [varchar](50) NOT NULL,
	[redeem_type] [varchar](50) NOT NULL,
	[redeem_limit] [int] NULL,
	[redeem_user] [varchar](50) NULL,
	[redeem_credit_config_id] [int] NOT NULL,
	[redeem_credit_amount] [int] NOT NULL,
	[status] [tinyint] NOT NULL,
 CONSTRAINT [PK_WEBENGINE_REDEEMCODE] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]


SET ANSI_PADDING OFF

ALTER TABLE [dbo].[WEBENGINE_REDEEMCODE] ADD  CONSTRAINT [DF_WEBENGINE_REDEEMCODE_status]  DEFAULT ((0)) FOR [status]

